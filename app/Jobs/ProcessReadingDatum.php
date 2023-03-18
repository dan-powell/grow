<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\{Datum, Figure};
use App\Notifications\{FigureAlertResolved, FigureAlertTriggered};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Notification;

class ProcessReadingDatum implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private Figure $figure;

    private int $value;

    private string $timestamp;

    /**
     * Create a new job instance.
     */
    public function __construct(Figure $figure, int $value, string $timestamp)
    {
        $this->figure = $figure;
        $this->value = $value;
        $this->timestamp = $timestamp;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Save the datum
        $datum = new Datum();
        $datum->value = $this->value;
        $datum->timestamp = $this->timestamp;
        $this->figure->data()->save($datum);

        // Check status of Figure Alerts
        $datum->refresh();
        $this->figure->loadMissing('alerts');
        $users = User::where('receive_alerts', true)->get();
        foreach ($this->figure->alerts as $alert) {
            // Check if alert
            if ($alert->enabled && !$alert->activated) {
                // Has the value tripped?
                if (
                    ($alert->lower && $datum->value_calibrated < $alert->value) ||
                    (!$alert->lower && $datum->value_calibrated > $alert->value)
                ) {
                    // Alert activated
                    $alert->activated = now();
                    $alert->save();
                    if ($alert->email) {
                        foreach ($users as $user) {
                            Notification::route('mail', [$user->email])->notify(new FigureAlertTriggered($this->figure, $alert, $datum));
                        }
                    }
                }
            } elseif ($alert->enabled && $alert->activated) {
                if (
                    ($alert->lower && $datum->value_calibrated > $alert->value) ||
                    (!$alert->lower && $datum->value_calibrated < $alert->value)
                ) {
                    // Alert timeout was activated, but we just recieved a reading so we should resolve it
                    $alert->activated = null;
                    $alert->save();
                    if ($alert->email) {
                        foreach ($users as $user) {
                            Notification::route('mail', [$user->email])->notify(new FigureAlertResolved($this->figure, $alert, $datum));
                        }
                    }
                }
            }
        }
    }
}
