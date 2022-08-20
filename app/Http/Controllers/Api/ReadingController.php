<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReadingRequest;
use Log;

class ReadingController extends Controller
{
    public function store(StoreReadingRequest $request)
    {
        Log::debug($request);
    }
}
