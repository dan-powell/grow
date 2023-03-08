<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Devices/Index', [
            'devices' => Device::with(['configs'])->get(),
        ]);
    }

    public function show($device, Request $request)
    {
        return Inertia::render('Devices/Show', [
            'device' => $device,
            'configs' => $device->configs,
        ]);
    }

    public function configdata($device, $config, Request $request)
    {
        return Inertia::render('Devices/Reading', [
            'device' => $device,
            'readings' => $device->readings,
            'config' => $config,
        ]);
    }
}
