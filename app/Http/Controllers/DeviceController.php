<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use carbon\Carbon;

class DeviceController extends Controller
{
    public function show($device, Request $request)
    {
        $device->loadMissing('figures');

        return Inertia::render('Devices/Show', [
            'device' => $device,
        ]);
    }

    public function history($device, $figure, Request $request)
    {
        return Inertia::render('Devices/History', [
            'device' => $device,
            'figure' => $figure,
            // TODO Make this use a user-set date-range
            'readings' => array_values($figure->data()->whereDate('timestamp', '>', Carbon::now()->subDays(30))->whereDate('timestamp', '<', Carbon::now())->orderBy('timestamp', 'desc')->limit(1000)->get()->toArray()),
        ]);
    }
}
