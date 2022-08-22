<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Device;
use App\Models\Reading;

class DashboardController extends Controller
{
    public function show()
    {
        return Inertia::render('Dashboard', [
            'devices' => Device::with(['reading_latest'])->get(),
            'reading_latest' => Reading::orderBy('timestamp')->with(['device.dataconfigs'])->first()
        ]);
    }
}
