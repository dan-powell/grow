<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReadingRequest;
use App\Models\{Datum, Device, User};
use Illuminate\Support\Facades\Notification;
use App\Notifications\DeviceAlertLateReadingResolved;

class ReadingController extends Controller
{
    public function store(StoreReadingRequest $request)
    {
        $messages = collect([]);
        $device = Device::where('nickname', $request->input('nickname'))->with(['figures'])->first();
        $users = User::where('receive_alerts', true)->get();

        if (!$device) {
            $messages[] = 'Device not found.';

            return response()->json([
                'status' => 'FAIL',
                'messages' => $messages,
            ], 404);
        }

        // TODO A lot of this should go in a queue to make it faster when recieving

        foreach ($request->input('readings') as $key => $value) {
            $figure = $device->figures->where('key', $key)->first();
            if ($figure) {
                $datum = new Datum();
                $datum->value = $value;
                $datum->timestamp = $request->input('timestamp');
                $figure->data()->save($datum);
                $messages[] = $key . ' data saved.';

                if($device->alert_activated) {
                    // Alert timeout was activated, but we just recieved an alert so we should cancel it
                    foreach ($users as $user) {
                        Notification::route('mail', [$user->email])->notify(new DeviceAlertLateReadingResolved($device));
                    }
                    $device->alert_activated = null;
                    $device->save();
                }

                // TODO Check status of Figure Alerts

            } else {
                $messages[] = $key . ' figure not found - data ignored.';
            }
        }

        return response()->json([
            'status' => 'OK',
            'messages' => $messages,
        ], 200);
    }
}
