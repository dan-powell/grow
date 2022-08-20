<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReadingRequest;
use Log;

use App\Models\Device;
use App\Models\Reading;
use App\Models\ReadingData;

class ReadingController extends Controller
{
    public function store(StoreReadingRequest $request)
    {
        $device = Device::where('nickname', $request->input('nickname'))->firstOrFail();
        
        $data = [];
        foreach($request->input('readings') as $name => $value) {
            $datum = new ReadingData();
            $datum->name = $name;
            $datum->value = $value;
            $data[] = $datum;
        }

        $reading = new Reading();
        $reading->device_id = $device->id;
        $reading->timestamp = $request->input('timestamp');
        $reading->save();
        $reading->data()->saveMany($data);

    }
}
