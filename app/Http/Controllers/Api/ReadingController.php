<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReadingRequest;
use Log;

use App\Models\Device;
use App\Models\Datum;

class ReadingController extends Controller
{
    public function store(StoreReadingRequest $request)
    {
        $messages = collect([]);
        $device = Device::where('nickname', $request->input('nickname'))->with(['figures'])->first();

        if(!$device) {
            $messages[] = 'Device not found.';
            return response()->json([
                'status' => 'FAIL',
                'messages' => $messages
            ], 404);
        }

        foreach($request->input('readings') as $key => $value) {
            $figure = $device->figures->where('key', $key)->first();
            if($figure) {
                $datum = new Datum();
                $datum->value = $value;
                $datum->timestamp = $request->input('timestamp');
                $figure->data()->save($datum);
                $messages[] = $key . ' data saved.';
            } else {
                $messages[] = $key . ' figure not found - data ignored.';
            }
        }
        return response()->json([
            'status' => 'OK',
            'messages' => $messages
        ], 200);
    }
}
