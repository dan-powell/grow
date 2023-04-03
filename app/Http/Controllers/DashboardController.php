<?php

namespace App\Http\Controllers;

use App\Models\{Device};
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show()
    {
        $devices_with_configs = Device::dashboard()->with(['figures'])->get();
        $devices_without_configs = Device::whereNotIn('id', $devices_with_configs->pluck('id'))->get();

        return Inertia::render('Dashboard', [
            'devices_with_configs' => $devices_with_configs,
            'devices_without_configs' => $devices_without_configs,
        ]);
    }
}
