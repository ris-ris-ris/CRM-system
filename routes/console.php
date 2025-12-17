<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\Email\ImapEmailSyncService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('emails:sync {--account_id=}', function () {
    $accountId = $this->option('account_id');

    $service = app(ImapEmailSyncService::class);
    $result = $service->sync($accountId ? (int) $accountId : null);

    $this->info('Email sync completed.');
    foreach ($result as $row) {
        $this->line(sprintf(
            '- account_id=%s email=%s created=%d skipped=%d errors=%d',
            $row['account_id'],
            $row['email'],
            $row['created'],
            $row['skipped'],
            $row['errors'],
        ));
    }

    return 0;
})->purpose('Sync incoming emails via IMAP');

// Планировщик (нужно запускать через cron schedule:run или локально schedule:work)
Schedule::command('emails:sync --quiet')->everyMinute()->withoutOverlapping();
