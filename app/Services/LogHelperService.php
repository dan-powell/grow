<?php

namespace App\Services;
use App\Models\Log;

class LogHelperService
{
    function create(string $name, string $summary = null, string $device = null): void
    {
        Log::create([
            'name' => $name,
            'summary' => $summary,
            'device_id' => $device,
            'icon' => 'fa-info'
        ]);
    }
}
