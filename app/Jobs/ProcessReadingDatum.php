<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Figure, Datum};

class ProcessReadingDatum implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        // TODO Check status of Figure Alerts
    }
}
