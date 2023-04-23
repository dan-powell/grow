<?php

namespace App\Services;
use App\Models\Log;
use App\Enum\Severity;
use App\Models\Device;

class LogHelperService
{

    private Log $log;

    function __construct()
    {
        $this->log = new Log;
    }

    function reset(): self
    {
        $this->log = new Log;
        return $this;
    }

    function create(string $name, string $summary = null, string $device = null): void
    {
        $this->log->create([
            'name' => $name,
            'summary' => $summary,
            'device_id' => $device,
        ]);
        $this->reset();
    }

    function name(string $name): self
    {
        $this->log->name = $name;
        return $this;
    }

    function summary(string $summary): self
    {
        $this->log->summary = $summary;
        return $this;
    }

    function description(string $description): self
    {
        $this->log->description = $description;
        return $this;
    }

    function warning(): self
    {
        $this->log->severity = Severity::WARNING->value;
        return $this;
    }

    function info(): self
    {
        $this->log->severity = Severity::INFO->value;
        return $this;
    }

    function success(): self
    {
        $this->log->severity = Severity::SUCCESS->value;
        return $this;
    }

    function device(Device $device): self
    {
        $this->log->device_id = $device->id;
        return $this;
    }

    function save(): void
    {
        $this->log->save();
        $this->reset(); // Reset for next Log
    }
}
