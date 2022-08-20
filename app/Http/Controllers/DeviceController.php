<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Device;

class DeviceController extends Controller
{
    public function show($name, Request $request)
    {
        $device = Device::where('name', $name)->with(['readings'])->firstOrFail();
        return Inertia::render('Devices/Show', [
            'device' => $device,
            'readings' => $device->readings
        ]);
    }
}
