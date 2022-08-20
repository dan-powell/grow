<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Device;

class HomeController extends Controller
{
    public function home()
    {
        return Inertia::render('Home', [
            'devices' => Device::all()
        ]);
    }
}
