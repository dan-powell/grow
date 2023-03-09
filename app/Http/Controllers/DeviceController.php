<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function show($device, Request $request)
    {
        $device->loadMissing('figures.data.figure');

        return Inertia::render('Devices/Show', [
            'device' => $device,
        ]);
    }

    public function history($device, $figure, Request $request)
    {
        $figure->loadMissing('data.figure');

        return Inertia::render('Devices/History', [
            'device' => $device,
            'figure' => $figure,
            'readings' => array_values($figure->data->sortByDesc('timestamp')->toArray()),
        ]);
    }
}
