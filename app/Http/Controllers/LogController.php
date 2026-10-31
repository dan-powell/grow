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
        $query = Log::orderBy('created_at', 'DESC');
        if ($request->input('severity')) {
            $query->where('severity', $request->input('severity'));
        }
        $logs = $query->paginate(16);

        return Inertia::render('Log/Index', [
            'logs' => $logs,
            'severities' => array_values(Severity::options()),
        ]);
    }
}
