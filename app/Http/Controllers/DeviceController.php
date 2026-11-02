<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function show($device, Request $request)
    {
        return Inertia::render('Devices/Show', [
            'device' => fn () => $device->loadMissing('figures.currentSubject'),
        ]);
    }

    public function history($device, $figure, Request $request)
    {
        return Inertia::render('Devices/History', [
            'device' => $device,
            'figure' => $figure->loadMissing('currentSubject'),
            // Subjects measured during the window, so the chart can mark where they changed
            'assignments' => fn () => $figure->assignments()
                ->with('subject')
                ->where('started_at', '<', Carbon::now())
                ->where(fn ($query) => $query->whereNull('ended_at')->orWhere('ended_at', '>', Carbon::now()->subDays(30)))
                ->get(),
            // TODO Make this use a user-set date-range
            'readings' => fn () => array_values($figure->data()->whereDate('timestamp', '>', Carbon::now()->subDays(30))->whereDate('timestamp', '<', Carbon::now())->orderBy('timestamp', 'desc')->limit(1000)->get()->toArray()),
        ]);
    }
}
