<?php

namespace App\Http\Controllers;

use App\Enum\Severity;
use App\Http\Requests\LogIndexRequest;
use App\Models\Log;
use Inertia\Inertia;

class LogController extends Controller
{
    public function index(LogIndexRequest $request)
    {
        return Inertia::render('Log/Index', [
            'logs' => fn () => Log::orderBy('created_at', 'DESC')
                ->when($request->input('severity'), fn ($query, $severity) => $query->where('severity', $severity))
                ->paginate(16),
            'severities' => array_values(Severity::options()),
        ]);
    }
}
