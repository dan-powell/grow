<?php

namespace App\Jobs;

use App\Facades\LogHelper;
use App\Models\Datum;
use App\Models\Figure;
use App\Models\User;
use App\Notifications\SubjectAlertResolved;
use App\Notifications\SubjectAlertTriggered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class ProcessReadingDatum implements ShouldQueue
{
    use Queueable;

    private Figure $figure;

    private float $value;

    private string $timestamp;

    /**
     * Create a new job instance.
     */
    public function __construct(Figure $figure, float $value, string $timestamp)
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
        $datum = new Datum;
        $datum->value = $this->value;
        $datum->timestamp = $this->timestamp;
        $this->figure->data()->save($datum);

        // Check alerts on the subject this figure was measuring when the reading was taken
        $subject = $this->figure->assignmentAt($this->timestamp)?->subject;
        if (! $subject) {
            return;
        }
        $datum->refresh();
        $users = User::where('receive_alerts', true)->get();
        foreach ($subject->alerts as $alert) {
            // Check if alert
            if ($alert->enabled && ! $alert->activated) {
                // Has the value tripped?
                if (
                    ($alert->lower && $datum->value_calibrated < $alert->value) ||
                    (! $alert->lower && $datum->value_calibrated > $alert->value)
                ) {
                    // Alert activated
                    $alert->activated = now();
                    $alert->save();
                    LogHelper::warning()->name('Alert Triggered')->summary('The reading value "'.$datum->value.'" has '.($alert->lower ? 'subceeded' : 'exceeded').' the alert by '.($alert->lower ? ($alert->value - $datum->value) : ($datum->value - $alert->value)))->device($this->figure->device)->save();
                    if ($alert->email) {
                        foreach ($users as $user) {
                            Notification::route('mail', [$user->email])->notify(new SubjectAlertTriggered($this->figure, $alert, $datum));
                        }
                    }
                }
            } elseif ($alert->enabled && $alert->activated) {
                if (
                    ($alert->lower && $datum->value_calibrated > $alert->value) ||
                    (! $alert->lower && $datum->value_calibrated < $alert->value)
                ) {
                    // Alert timeout was activated, but we just recieved a reading so we should resolve it
                    $alert->activated = null;
                    $alert->save();
                    LogHelper::success()->name('Alert Resolved')->summary('An alert for '.$subject->name.' ('.$this->figure->device->name.') has been resolved')->device($this->figure->device)->save();
                    if ($alert->email) {
                        foreach ($users as $user) {
                            Notification::route('mail', [$user->email])->notify(new SubjectAlertResolved($this->figure, $alert, $datum));
                        }
                    }
                }
            }
        }
    }
}
