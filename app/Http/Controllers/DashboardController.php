<?php

namespace App\Http\Controllers;

use App\Models\{Device, Figure};
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show()
    {
        $devices = Device::orderBy('order')->get();
        $figures = Figure::dashboard()->with(['device'])->orderBy('order')->get();
        return Inertia::render('Dashboard', [
            'devices' => $devices,
            'figures' => $figures
        ]);
    }
}
