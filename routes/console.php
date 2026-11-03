<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('reading:check')->hourly();
Schedule::command('cache:prune-stale-tags')->hourly();
// Deletes readings past `site.reading_retention_days`; does nothing while that is unset
Schedule::command('model:prune')->daily();
