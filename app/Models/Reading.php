<?php

namespace App\Models;

use App\Models\{Device, ReadingData};
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};

class Reading extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'reading';

    public $timestamps = true;

    protected $fillable = ['timestamp'];

    protected $hidden = [
        'data',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    protected $appends = [
        'timestamp_formatted',
        'created_at_formatted',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Always eager-load data
        static::addGlobalScope('eager', function (Builder $builder) {
            $builder->with(['device.configs', 'data']);
        });

        // On record creation
        static::created(function (self $reading) {
            // Reset the last alert time
            $reading->device->reading_alert_last = null;
            $reading->device->save();
        });

        // On record retrieval
        static::retrieved(function (self $reading) {
            // Collect datapoints to add to model as attributes
            $datapoints = [];
            foreach ($reading->data as $datum) {
                // Find the relevant config
                $config = $reading->device->configs->keyBy('key')[$datum->key] ?? null;
                if ($config) {
                    // If we have config, then
                    $datapoint = $config;
                    // Calibrate the value
                    $datapoint->value = $reading->calibrateDataValue($config, $datum->value);
                    $datapoint->value_formatted = $config->prefix ?? '' . $reading->calibrateDataValue($config, $datum->value) . $config->suffix ?? '';
                    $datapoints[$config->key] = $datapoint;
                }
            }
            // Add datapoints to model
            $reading->datapoints = $datapoints;
        });
    }

    protected function getTimestampFormattedAttribute()
    {
        return $this->timestamp->format('dS M H:i');
    }

    protected function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('dS M H:i');
    }

    protected function calibrateDataValue($config, $value)
    {
        // Does the value require adjustment?
        if ($config->calibrate && $config->calibrate_value) {
            if ($config->calibrate_percentage) {
                // Adjust by a percentage
                $value += ($value / 100) * $config->calibrate_value;
            } else {
                // Adjust by a simple value
                $value += $config->calibrate_value;
            }
        }

        return $value;
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function data()
    {
        return $this->hasMany(ReadingData::class);
    }
}
