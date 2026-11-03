<?php

namespace App\Http\Controllers;

use App\Models\Datum;
use App\Models\Figure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function show($device, Request $request)
    {
        abort_unless($device->enabled, 404);

        return Inertia::render('Devices/Show', [
            'device' => fn () => $device->loadMissing('figures.currentSubject'),
            // Last 30 days of calibrated readings keyed by figure ID, for the combined chart
            'readings' => Inertia::defer(fn () => $device->loadMissing('figures')->figures->mapWithKeys(fn (Figure $figure) => [
                $figure->id => $figure->data()
                    ->where('timestamp', '>', Carbon::now()->subDays(30))
                    ->orderBy('timestamp', 'desc')
                    ->limit(1000)
                    ->get(['figure_id', 'timestamp', 'value'])
                    ->map(fn (Datum $datum) => [
                        'x' => $datum->timestamp->toIso8601String(),
                        'y' => $datum->setRelation('figure', $figure)->value_calibrated,
                    ]),
            ])),
        ]);
    }

    public function history($device, $figure, Request $request)
    {
        abort_unless($device->enabled, 404);

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
