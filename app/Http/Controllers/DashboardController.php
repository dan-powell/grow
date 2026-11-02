<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Figure;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show()
    {
        return Inertia::render('Dashboard', [
            'devices' => fn () => Device::orderBy('order')->get(),
            'figures' => fn () => Figure::dashboard()->with(['device', 'currentSubject'])->orderBy('order')->get(),
        ]);
    }
}
