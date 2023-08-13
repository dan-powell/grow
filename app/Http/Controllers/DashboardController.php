<?php

namespace App\Http\Controllers;

use App\Models\{Device, Figure};
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show()
    {
        $devices_with_configs = Device::dashboard()->with(['figures'])->orderBy('order')->get();
        $devices_without_configs = Device::whereNotIn('id', $devices_with_configs->pluck('id'))->orderBy('order')->get();

        $figures = Figure::dashboard()->with(['device'])->get();

        return Inertia::render('Dashboard', [
            'devices_with_configs' => $devices_with_configs,
            'devices_without_configs' => $devices_without_configs,
            'figures' => $figures
        ]);
    }
}
