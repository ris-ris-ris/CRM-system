<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\EmailAccount;
use App\Models\EmailRelation;
use App\Models\Deal;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Email\ImapEmailSyncService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as MimeEmail;

class EmailController extends Controller
{
    /**
     * Получить письма для сделки/компании/контакта
     */
    public function getEmails(Request $request)
    {
        $relatedType = $request->query('related_type'); // Deal, Company, Contact
        $relatedId = $request->query('related_id');
        $dealId = $request->query('deal_id'); // Для обратной совместимости
        
        $user = auth()->user();
        
        // Определяем связанную сущность
        if ($dealId) {
            $deal = Deal::find($dealId);
            if (!$deal) {
                return response()->json(['error' => 'Сделка не найдена'], 404);
            }
            
            // Проверка прав доступа
            if (!$user->is_admin && $user->role !== 'manager' && $deal->user_id !== $user->id) {
                return response()->json(['error' => 'Доступ запрещен'], 403);
            }
            
            $relatedType = 'Deal';
            $relatedId = $dealId;
        } else if ($relatedType && $relatedId) {
            // Проверка прав доступа в зависимости от типа
            if ($relatedType === 'Deal') {
                $deal = Deal::find($relatedId);
                if (!$deal) {
                    return response()->json(['error' => 'Сделка не найдена'], 404);
                }
                if (!$user->is_admin && $user->role !== 'manager' && $deal->user_id !== $user->id) {
                    return response()->json(['error' => 'Доступ запрещен'], 403);
                }
            }
        } else {
            return response()->json(['error' => 'Не указана связанная сущность'], 400);
        }
        
        // Получаем письма, связанные с этой сущностью
        $query = Email::whereHas('relations', function($q) use ($relatedType, $relatedId) {
            $q->where('related_type', $relatedType)->where('related_id', $relatedId);
        });
        
        // Если указан contact_id, фильтруем письма только с этим контактом
        if ($request->has('contact_id') && $request->query('contact_id')) {
            $contactId = $request->query('contact_id');
            $contact = Contact::find($contactId);
            if ($contact && $contact->email) {
                // Показываем только письма, где контакт - получатель или отправитель
                $query->where(function($q) use ($contact) {
                    $q->where('to_email', 'like', '%' . $contact->email . '%')
                      ->orWhere('from_email', $contact->email);
                });
            }
        }
        
        $emails = $query->with(['attachments', 'emailAccount'])
            ->orderBy('sent_at', 'desc')
            ->orderBy('received_at', 'desc')
            ->get();
        
        return response()->json($emails);
    }
    
    /**
     * Отправить письмо
     */
    public function sendEmail(Request $request)
    {
        // Убеждаемся, что ответ будет JSON
        $request->headers->set('Accept', 'application/json');
        
        $user = auth()->user();
        
        $data = $request->validate([
            'email_account_id' => 'nullable|integer|exists:email_accounts,id',
            'to_email' => 'required|email',
            'to_name' => 'nullable|string',
            'subject' => 'required|string|max:500',
            'body' => 'required|string',
            'deal_id' => 'nullable|exists:deals,id',
            'company_id' => 'nullable|exists:companies,id',
            'contact_id' => 'nullable|exists:contacts,id',
        ]);
        
        $deal = null;

        // Проверка прав доступа для сделки
        if (!empty($data['deal_id'])) {
            $deal = Deal::find($data['deal_id']);
            if (!$user->is_admin && $user->role !== 'manager' && $deal->user_id !== $user->id) {
                return response()->json(['error' => 'Доступ запрещен'], 403);
            }
        }

        // Если company_id не передали, но письмо отправляем "из диалога сделки" — подтянем company_id из сделки
        if (empty($data['company_id']) && $deal && $deal->company_id) {
            $data['company_id'] = $deal->company_id;
        }
        
        // 1) Если передали email_account_id — используем его
        $emailAccount = null;
        if (!empty($data['email_account_id'])) {
            $emailAccount = EmailAccount::where('id', (int)$data['email_account_id'])
                ->where('is_active', true)
                ->first();
        }

        // 2) Иначе используем глобальный EmailAccount (a.avoutz@gmail.com) для всех пользователей
        if (!$emailAccount) {
            $emailAccount = EmailAccount::where('is_active', true)
                ->where('email', 'a.avoutz@gmail.com')
                ->first();
        }
        
        // Если нет глобального, ищем аккаунт пользователя
        if (!$emailAccount) {
            $emailAccount = EmailAccount::where('user_id', $user->id)
                ->where('is_active', true)
                ->first();
        }
        
        // Если все еще нет, создаем на основе настроек из .env
        if (!$emailAccount) {
            $smtpPassword = config('mail.mailers.smtp.password');
            if (!$smtpPassword) {
                return response()->json(['error' => 'Почтовый аккаунт не настроен. Обратитесь к администратору.'], 400);
            }
            
            $emailAccount = new EmailAccount();
            $emailAccount->user_id = $user->id;
            $emailAccount->email = 'a.avoutz@gmail.com'; // Всегда используем этот email
            $emailAccount->provider = 'gmail';
            // IMAP для получения ответов (синхронизация входящих)
            $emailAccount->imap_host = 'imap.gmail.com';
            $emailAccount->imap_port = 993;
            $emailAccount->imap_ssl = true;
            $emailAccount->smtp_host = config('mail.mailers.smtp.host', 'smtp.gmail.com');
            $emailAccount->smtp_port = config('mail.mailers.smtp.port', 587);
            $emailAccount->smtp_ssl = true;
            $emailAccount->smtp_username = 'a.avoutz@gmail.com';
            $emailAccount->password = $smtpPassword;
            $emailAccount->is_active = true;
            $emailAccount->save();
        }
        
        try {
            // Настраиваем SMTP из EmailAccount
            // Используем smtp_password, если есть, иначе password
            $smtpPassword = $emailAccount->smtp_password ?? $emailAccount->password ?? config('mail.mailers.smtp.password');

            $host = $emailAccount->smtp_host ?? 'smtp.gmail.com';
            $username = $emailAccount->smtp_username ?? $emailAccount->email;

            // Генерируем Message-ID и будем сохранять его в БД.
            // Важно: этот же Message-ID попадет в заголовки письма, чтобы ответ клиента
            // пришел с In-Reply-To и мы могли "приклеить" его в тот же диалог.
            $appDomain = parse_url((string) config('app.url', 'http://localhost'), PHP_URL_HOST) ?: 'crm.local';
            $messageId = '<sent_' . Str::uuid()->toString() . '@' . $appDomain . '>';
            $threadId = $messageId; // по умолчанию: корневое сообщение = корень треда

            // Если это "диалог сделки + контакт" и уже есть переписка — продолжаем тот же thread_id,
            // и проставляем In-Reply-To на последнее письмо треда, чтобы почтовики точно склеили цепочку.
            $replyToEmail = null;
            if (!empty($data['deal_id']) && !empty($data['contact_id'])) {
                $replyToEmail = Email::query()
                    ->whereHas('relations', fn($q) => $q->where('related_type', 'Deal')->where('related_id', (int)$data['deal_id']))
                    ->whereHas('relations', fn($q) => $q->where('related_type', 'Contact')->where('related_id', (int)$data['contact_id']))
                    ->orderByRaw('COALESCE(sent_at, received_at, created_at) DESC')
                    ->first();

                if ($replyToEmail && $replyToEmail->thread_id) {
                    $threadId = $replyToEmail->thread_id;
                }
            }

            // Получаем список IPv4 адресов (в Docker часто один IP может быть недоступен)
            // и пробуем их по очереди. Если не получилось — пробуем hostname как есть.
            $targets = [];
            if (filter_var($host, FILTER_VALIDATE_IP)) {
                $targets = [$host];
            } else {
                try {
                    $dns = dns_get_record($host, DNS_A) ?: [];
                    foreach ($dns as $rec) {
                        if (!empty($rec['ip'])) {
                            $targets[] = $rec['ip'];
                        }
                    }
                } catch (\Throwable) {
                    $targets = [];
                }
                $targets = array_values(array_unique(array_filter($targets)));
                if (count($targets) === 0) {
                    $targets = [$host];
                }
            }

            // Пытаемся smtps:465, затем smtp:587 с auto_tls (STARTTLS)
            $attempts = [
                ['scheme' => 'smtps', 'port' => 465, 'auto_tls' => 0],
                ['scheme' => 'smtp', 'port' => 587, 'auto_tls' => 1],
            ];

            $lastError = null;
            $sent = false;

            foreach ($attempts as $a) {
                foreach ($targets as $target) {
                    try {
                        $dsn = sprintf(
                            '%s://%s:%s@%s:%d?verify_peer=0&auto_tls=%d',
                            $a['scheme'],
                            rawurlencode($username),
                            rawurlencode($smtpPassword),
                            $target,
                            $a['port'],
                            $a['auto_tls']
                        );

                        $transport = Transport::fromDsn($dsn);

                        // Настраиваем таймаут + SNI (peer_name) на исходный hostname
                        if (method_exists($transport, 'getStream')) {
                            $stream = $transport->getStream();
                            if (method_exists($stream, 'setTimeout')) {
                                $stream->setTimeout(20);
                            }
                            if (method_exists($stream, 'getStreamOptions') && method_exists($stream, 'setStreamOptions')) {
                                $opts = $stream->getStreamOptions();
                                $opts['ssl']['peer_name'] = $host;
                                $opts['ssl']['verify_peer'] = false;
                                $opts['ssl']['verify_peer_name'] = false;
                                $stream->setStreamOptions($opts);
                            }
                        }

                        $mailer = new Mailer($transport);

                        $message = (new MimeEmail())
                            ->from(new Address($emailAccount->email, $user->name))
                            ->to(new Address($data['to_email'], $data['to_name'] ?? ''))
                            ->subject($data['subject'])
                            ->text($data['body']);

                        // Проставляем Message-ID на исходящее письмо (нужно для связывания ответов).
                        // addIdHeader сам оформит значение в "<...>".
                        $message->getHeaders()->addIdHeader('Message-ID', trim($messageId, '<>'));

                        // Если продолжаем тред — добавляем In-Reply-To (и References)
                        if ($replyToEmail && $replyToEmail->message_id) {
                            $message->getHeaders()->addIdHeader('In-Reply-To', trim($replyToEmail->message_id, '<>'));
                            $message->getHeaders()->addTextHeader('References', $replyToEmail->message_id);
                        }

                        $mailer->send($message);
                        $sent = true;
                        break 2;
                    } catch (TransportExceptionInterface $e) {
                        $lastError = $e->getMessage();
                    } catch (\Throwable $e) {
                        $lastError = $e->getMessage();
                    }
                }
            }

            // Dev fallback: если SMTP провайдера недоступен из Docker (часто заблокированы 465/587),
            // отправляем в Mailpit, чтобы функционал диалога работал локально.
            if (! $sent && app()->environment('local')) {
                try {
                    $transport = Transport::fromDsn('smtp://mailpit:1025?auto_tls=0&verify_peer=0');
                    $mailer = new Mailer($transport);

                    $message = (new MimeEmail())
                        ->from(new Address($emailAccount->email, $user->name))
                        ->to(new Address($data['to_email'], $data['to_name'] ?? ''))
                        ->subject($data['subject'])
                        ->text($data['body']);

                    $message->getHeaders()->addIdHeader('Message-ID', trim($messageId, '<>'));
                    if ($replyToEmail && $replyToEmail->message_id) {
                        $message->getHeaders()->addIdHeader('In-Reply-To', trim($replyToEmail->message_id, '<>'));
                        $message->getHeaders()->addTextHeader('References', $replyToEmail->message_id);
                    }

                    $mailer->send($message);
                    $sent = true;
                } catch (\Throwable $e) {
                    $lastError = $e->getMessage();
                }
            }

            if (!$sent) {
                throw new \Exception('Не удалось отправить письмо через SMTP. Последняя ошибка: ' . ($lastError ?? 'Неизвестная ошибка'));
            }
            
            // Сохраняем письмо в БД
            $email = Email::create([
                'email_account_id' => $emailAccount->id,
                'message_id' => $messageId,
                'thread_id' => $threadId,
                'from_email' => $emailAccount->email,
                'from_name' => $user->name,
                'to_email' => $data['to_email'],
                'to_name' => $data['to_name'] ?? '',
                'subject' => $data['subject'],
                'body_html' => nl2br(e($data['body'])),
                'body_text' => $data['body'],
                'direction' => 'outgoing',
                'sent_at' => now(),
                'is_read' => true,
            ]);
            
            // Привязываем к сущностям
            if (isset($data['deal_id'])) {
                EmailRelation::create([
                    'email_id' => $email->id,
                    'related_type' => 'Deal',
                    'related_id' => $data['deal_id'],
                ]);
            }
            if (isset($data['company_id'])) {
                EmailRelation::create([
                    'email_id' => $email->id,
                    'related_type' => 'Company',
                    'related_id' => $data['company_id'],
                ]);
            }
            if (isset($data['contact_id'])) {
                EmailRelation::create([
                    'email_id' => $email->id,
                    'related_type' => 'Contact',
                    'related_id' => $data['contact_id'],
                ]);
            }
            
            return response()->json($email->load(['attachments', 'emailAccount']), 201);
        } catch (\Exception $e) {
            \Log::error('Email send error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Ошибка отправки: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Получить письма для сделки (для обратной совместимости)
     */
    public function getDealEmails($dealId)
    {
        return $this->getEmails(new Request(['deal_id' => $dealId]));
    }

    /**
     * Принудительно синхронизировать входящие письма (IMAP).
     * Нужно, чтобы ответы контактов попадали в тот же диалог по In-Reply-To / References.
     */
    public function syncIncoming(Request $request, ImapEmailSyncService $sync)
    {
        $data = $request->validate([
            'account_id' => 'nullable|integer|exists:email_accounts,id',
        ]);

        $result = $sync->sync(isset($data['account_id']) ? (int) $data['account_id'] : null);

        return response()->json([
            'ok' => true,
            'result' => $result,
        ]);
    }
}
