<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Log;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = Log::all();
        return Inertia::render('Log/Index', [
            'logs' => $logs,
        ]);
    }
}
