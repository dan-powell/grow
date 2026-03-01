<?php

namespace App\Services;

use App\Enum\Severity;
use App\Models\Device;
use App\Models\Log;

class LogHelperService
{
    private Log $log;

    public function __construct()
    {
        $this->log = new Log;
    }

    public function reset(): self
    {
        $this->log = new Log;

        return $this;
    }

    public function create(string $name, ?string $summary = null, ?string $device = null): void
    {
        $this->log->create([
            'name' => $name,
            'summary' => $summary,
            'device_id' => $device,
        ]);
        $this->reset();
    }

    public function name(string $name): self
    {
        $this->log->name = $name;

        return $this;
    }

    public function summary(string $summary): self
    {
        $this->log->summary = $summary;

        return $this;
    }

    public function description(string $description): self
    {
        $this->log->description = $description;

        return $this;
    }

    public function warning(): self
    {
        $this->log->severity = Severity::WARNING->value;

        return $this;
    }

    public function info(): self
    {
        $this->log->severity = Severity::INFO->value;

        return $this;
    }

    public function success(): self
    {
        $this->log->severity = Severity::SUCCESS->value;

        return $this;
    }

    public function device(Device $device): self
    {
        $this->log->device_id = $device->id;

        return $this;
    }

    public function save(): void
    {
        $this->log->save();
        $this->reset(); // Reset for next Log
    }
}
