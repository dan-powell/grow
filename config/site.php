<?php

declare(strict_types=1);

return [
    'alert_email' => 'dan@autumnrain.co.uk',

    // Timezone readings and log entries are shown in. Times are stored in `app.timezone`.
    'display_timezone' => env('DISPLAY_TIMEZONE', 'Europe/London'),

    // Readings older than this many days are deleted by `model:prune`. Null keeps them forever.
    'reading_retention_days' => env('READING_RETENTION_DAYS'),
];
