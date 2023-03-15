<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReadingRequest;
use App\Jobs\ProcessReading;

class ReadingController extends Controller
{
    public function store(StoreReadingRequest $request)
    {
        ProcessReading::dispatch($request->input('nickname'), $request->input('readings'), $request->input('timestamp'));

        return response()->json([
            'status' => 'OK',
        ], 200);
    }
}
