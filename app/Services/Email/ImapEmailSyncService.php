<?php

namespace App\Services\Email;

use App\Models\Contact;
use App\Models\Email;
use App\Models\EmailAccount;
use App\Models\EmailRelation;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImapEmailSyncService
{
    /**
     * @return array<int, array{account_id:int,email:string,created:int,skipped:int,errors:int}>
     */
    public function sync(?int $accountId = null): array
    {
        $accounts = EmailAccount::query()
            ->where('is_active', true)
            ->when($accountId, fn ($q) => $q->where('id', $accountId))
            ->get();

        $result = [];
        foreach ($accounts as $account) {
            $result[] = $this->syncAccount($account);
        }

        return $result;
    }

    /**
     * @return array{account_id:int,email:string,created:int,skipped:int,errors:int}
     */
    private function syncAccount(EmailAccount $account): array
    {
        $created = 0;
        $skipped = 0;
        $errors = 0;

        if (! function_exists('imap_open')) {
            throw new \RuntimeException('PHP extension "imap" is not installed/enabled in the webserver container.');
        }

        if (! $account->imap_host) {
            // IMAP не настроен — просто пропускаем.
            return [
                'account_id' => (int) $account->id,
                'email' => (string) $account->email,
                'created' => 0,
                'skipped' => 0,
                'errors' => 0,
            ];
        }

        $mailbox = $this->buildMailbox($account);
        // Для IMAP почти всегда логин = email ящика (а не smtp_username).
        $username = (string) $account->email;
        $password = (string) ($account->password ?: '');

        $inbox = @imap_open($mailbox, $username, $password);
        if (! $inbox) {
            $errors++;
            Log::warning('IMAP open failed', [
                'email_account_id' => $account->id,
                'mailbox' => $mailbox,
                'imap_error' => imap_last_error(),
            ]);

            return [
                'account_id' => (int) $account->id,
                'email' => (string) $account->email,
                'created' => 0,
                'skipped' => 0,
                'errors' => $errors,
            ];
        }

        try {
            $since = $account->last_sync_at
                ? CarbonImmutable::parse($account->last_sync_at)->subMinutes(5)
                : CarbonImmutable::now()->subDays(7);

            $criteria = 'SINCE "'.$since->format('d-M-Y').'"';
            $uids = imap_search($inbox, $criteria, SE_UID) ?: [];

            foreach ($uids as $uid) {
                try {
                    $rawHeader = (string) imap_fetchheader($inbox, $uid, FT_UID);
                    $parsed = imap_rfc822_parse_headers($rawHeader);

                    $messageId = $this->normalizeMessageId($this->extractHeaderId($rawHeader, 'Message-ID'))
                        ?: $this->normalizeMessageId($parsed->message_id ?? null);

                    if (! $messageId) {
                        $appDomain = 'crm.local';
                        $messageId = '<imap_' . Str::uuid()->toString() . '@' . $appDomain . '>';
                    }

                    if (Email::where('message_id', $messageId)->exists()) {
                        $skipped++;
                        continue;
                    }

                    $inReplyTo = $this->normalizeMessageId(
                        $this->extractHeaderId($rawHeader, 'In-Reply-To') ?? ($parsed->in_reply_to ?? null)
                    );

                    $references = $this->extractReferences($rawHeader, $parsed->references ?? null);

                    $fromEmail = $this->parseFirstAddress($parsed->from ?? null);
                    $toEmail = $this->parseAddressList($parsed->to ?? null);
                    $subject = isset($parsed->subject) ? (string) $parsed->subject : '';

                    $receivedAt = null;
                    if (! empty($parsed->date)) {
                        try {
                            $receivedAt = CarbonImmutable::parse((string) $parsed->date);
                        } catch (\Throwable) {
                            $receivedAt = null;
                        }
                    }

                    [$bodyText, $bodyHtml] = $this->fetchBestBody($inbox, $uid);

                    $refEmail = $this->findReferencedEmail($inReplyTo, $references);

                    // В этой CRM мы кладем в БД только "ответы" на письма из CRM,
                    // чтобы входящие не разъезжались по диалогам сами по себе.
                    // Это обеспечивает требование: ответ попадает только в тот же диалог.
                    if (! $refEmail) {
                        $skipped++;
                        continue;
                    }

                    $threadId = $refEmail->thread_id ?: $refEmail->message_id ?: $messageId;

                    $email = Email::create([
                        'email_account_id' => $account->id,
                        'message_id' => $messageId,
                        'thread_id' => $threadId,
                        'from_email' => $fromEmail ?: '',
                        'from_name' => null,
                        'to_email' => $toEmail,
                        'to_name' => null,
                        'subject' => $subject,
                        'body_html' => $bodyHtml,
                        'body_text' => $bodyText,
                        'direction' => 'incoming',
                        'received_at' => $receivedAt ?: now(),
                        'is_read' => false,
                    ]);

                    // Копируем связи (Deal/Company/Contact), чтобы письмо попало ровно в тот же диалог.
                    $relations = EmailRelation::where('email_id', $refEmail->id)->get();
                    foreach ($relations as $rel) {
                        EmailRelation::firstOrCreate([
                            'email_id' => $email->id,
                            'related_type' => $rel->related_type,
                            'related_id' => $rel->related_id,
                        ]);
                    }

                    // 2) Если не нашли связь с контактом — пробуем определить контакт по from_email.
                    if ($fromEmail) {
                        $contact = Contact::where('email', $fromEmail)->first();
                        if ($contact) {
                            EmailRelation::firstOrCreate([
                                'email_id' => $email->id,
                                'related_type' => 'Contact',
                                'related_id' => $contact->id,
                            ]);
                        }
                    }

                    $created++;
                } catch (\Throwable $e) {
                    $errors++;
                    Log::warning('IMAP sync message failed', [
                        'email_account_id' => $account->id,
                        'uid' => $uid,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $account->last_sync_at = now();
            $account->save();
        } finally {
            imap_close($inbox);
        }

        return [
            'account_id' => (int) $account->id,
            'email' => (string) $account->email,
            'created' => $created,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    private function buildMailbox(EmailAccount $account): string
    {
        $host = (string) $account->imap_host;
        $port = (int) ($account->imap_port ?: 993);
        $ssl = $account->imap_ssl ? '/ssl' : '';

        return sprintf('{%s:%d/imap%s}INBOX', $host, $port, $ssl);
    }

    private function extractHeaderId(string $rawHeader, string $name): ?string
    {
        if (preg_match('/^'.preg_quote($name, '/').':\s*(.+)$/mi', $rawHeader, $m)) {
            return trim((string) $m[1]);
        }

        return null;
    }

    /**
     * Normalize to "<...>" form.
     */
    private function normalizeMessageId(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $value = trim($value);
        // sometimes headers contain multiple ids; take first token-like part
        if (preg_match('/<[^>]+>/', $value, $m)) {
            return $m[0];
        }

        // Strip surrounding quotes / brackets if any
        $value = trim($value, " \t\n\r\0\x0B\"'");

        if ($value === '') {
            return null;
        }

        return str_starts_with($value, '<') ? $value : '<'.$value.'>';
    }

    /**
     * @return array<int, string>
     */
    private function extractReferences(string $rawHeader, mixed $parsedReferences): array
    {
        $refs = [];

        $headerRefs = $this->extractHeaderId($rawHeader, 'References');
        if ($headerRefs) {
            preg_match_all('/<[^>]+>/', $headerRefs, $m);
            foreach (($m[0] ?? []) as $id) {
                $n = $this->normalizeMessageId($id);
                if ($n) {
                    $refs[] = $n;
                }
            }
        }

        if (is_string($parsedReferences) && $parsedReferences !== '') {
            preg_match_all('/<[^>]+>/', $parsedReferences, $m);
            foreach (($m[0] ?? []) as $id) {
                $n = $this->normalizeMessageId($id);
                if ($n) {
                    $refs[] = $n;
                }
            }
        }

        return array_values(array_unique($refs));
    }

    private function parseFirstAddress(mixed $list): ?string
    {
        if (! is_array($list) || count($list) === 0) {
            return null;
        }

        $addr = $list[0] ?? null;
        if (! is_object($addr) || empty($addr->mailbox) || empty($addr->host)) {
            return null;
        }

        return strtolower((string) $addr->mailbox.'@'.$addr->host);
    }

    private function parseAddressList(mixed $list): string
    {
        if (! is_array($list) || count($list) === 0) {
            return '';
        }

        $out = [];
        foreach ($list as $addr) {
            if (is_object($addr) && ! empty($addr->mailbox) && ! empty($addr->host)) {
                $out[] = strtolower((string) $addr->mailbox.'@'.$addr->host);
            }
        }

        return implode(', ', $out);
    }

    /**
     * @return array{0:?string,1:?string} [text, html]
     */
    private function fetchBestBody($inbox, int $uid): array
    {
        $structure = imap_fetchstructure($inbox, $uid, FT_UID);
        if (! $structure) {
            $body = imap_body($inbox, $uid, FT_UID);
            return [$body ?: null, null];
        }

        $bestText = null;
        $bestHtml = null;

        $parts = $structure->parts ?? null;
        if (! is_array($parts)) {
            $body = imap_body($inbox, $uid, FT_UID);
            return [$body ?: null, null];
        }

        foreach ($parts as $i => $part) {
            $partNumber = (string) ($i + 1);
            $mime = $this->getPartMime($part);

            if ($mime !== 'text/plain' && $mime !== 'text/html') {
                continue;
            }

            $raw = imap_fetchbody($inbox, $uid, $partNumber, FT_UID);
            $decoded = $this->decodePart($raw, (int) ($part->encoding ?? 0));

            if ($mime === 'text/plain' && $bestText === null) {
                $bestText = $decoded;
            }

            if ($mime === 'text/html' && $bestHtml === null) {
                $bestHtml = $decoded;
            }

            if ($bestText !== null && $bestHtml !== null) {
                break;
            }
        }

        return [$bestText, $bestHtml];
    }

    private function getPartMime(object $part): string
    {
        $typeMap = [
            0 => 'text',
            1 => 'multipart',
            2 => 'message',
            3 => 'application',
            4 => 'audio',
            5 => 'image',
            6 => 'video',
            7 => 'other',
        ];

        $type = $typeMap[$part->type ?? 7] ?? 'other';
        $subtype = isset($part->subtype) ? strtolower((string) $part->subtype) : 'plain';

        return strtolower($type.'/'.$subtype);
    }

    private function decodePart(string $data, int $encoding): string
    {
        return match ($encoding) {
            3 => base64_decode($data) ?: '',
            4 => quoted_printable_decode($data),
            default => $data,
        };
    }

    /**
     * @param array<int, string> $references
     */
    private function findReferencedEmail(?string $inReplyTo, array $references): ?Email
    {
        $candidates = [];
        if ($inReplyTo) {
            $candidates[] = $inReplyTo;
        }
        foreach ($references as $ref) {
            $candidates[] = $ref;
        }
        $candidates = array_values(array_unique(array_filter($candidates)));

        if (count($candidates) === 0) {
            return null;
        }

        return Email::whereIn('message_id', $candidates)->orderBy('id', 'desc')->first();
    }
}

