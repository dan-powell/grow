<?php

declare(strict_types=1);

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

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Figure $figure,
        private float $value,
        private string $timestamp,
    ) {}

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
        $alerts = $subject?->alerts()->where('enabled', true)->get();
        if (! $alerts || $alerts->isEmpty()) {
            return;
        }

        $datum->refresh()->setRelation('figure', $this->figure);
        $value = (float) $datum->value_calibrated;
        $device = $this->figure->device;
        $users = User::receivesAlerts()->get();

        foreach ($alerts as $alert) {
            if (! $alert->activated && $alert->isTrippedBy($value)) {
                $alert->activated = now();
                $alert->save();
                LogHelper::warning()->name('Alert Triggered')->summary($alert->breachSummary($value))->device($device)->save();
                if ($alert->email) {
                    Notification::send($users, new SubjectAlertTriggered($this->figure, $alert, $datum));
                }
            } elseif ($alert->activated && $alert->isResolvedBy($value)) {
                $alert->activated = null;
                $alert->save();
                LogHelper::success()->name('Alert Resolved')->summary('An alert for '.$subject->name.' ('.$device->name.') has been resolved')->device($device)->save();
                if ($alert->email) {
                    Notification::send($users, new SubjectAlertResolved($this->figure, $alert, $datum));
                }
            }
        }
    }
}
