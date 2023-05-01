<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Log;
use App\Http\Requests\LogIndexRequest;
use App\Enum\Severity;

class LogController extends Controller
{
    public function index(LogIndexRequest $request, )
    {
        $query = Log::orderBy('created_at', 'DESC');
        if($request->get('severity')) {
            $query->where('severity', $request->get('severity'));
        }
        $logs = $query->paginate(16);

        return Inertia::render('Log/Index', [
            'logs' => $logs,
            'severities' => array_values(Severity::options())
        ]);
    }
}
