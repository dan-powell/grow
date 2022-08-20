<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Device;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Devices/Index', [
            'devices' => Device::with(['dataconfigs'])->get(),
        ]);
    }

    public function show($device, Request $request)
    {
        return Inertia::render('Devices/Show', [
            'device' => $device,
            'dataconfigs' => $device->dataconfigs,
        ]);
    }

    public function reading($device, $dataconfig, Request $request)
    {
        return Inertia::render('Devices/Reading', [
            'device' => $device,
            'readings' => $device->readings,
            'dataconfig' => $dataconfig,
        ]);
    }
}
