<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Device;

class DashboardController extends Controller
{
    public function show()
    {
        return Inertia::render('Dashboard', [
            'devices' => Device::all()
        ]);
    }
}
