<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Device;
use App\Models\ReadingData;

class Reading extends Model
{
    protected $table = 'reading';

    public $timestamps = true;

    protected $fillable = ['timestamp'];

    protected $hidden = [
        'data'
    ];

    protected $dates = [
        'timestamp'
    ];

    protected $appends = [
        'timestamp_formatted'
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
            $builder->with(['device.dataconfigs', 'data']);
        });

        // On record retrieval
        static::retrieved(function ($reading) {
            // Collect datapoints to add to model as attributes
            $datapoints = [];
            foreach($reading->data as $datum) {
                // Find the relevant config
                $config = $reading->device->dataconfigs->keyBy('key')[$datum->key] ?? null;
                if($config) {
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

    protected function calibrateDataValue($config, $value)
    {
        // Does the value require adjustment?
        if($config->calibrate && $config->calibrate_value) {
            if($config->calibrate_percentage) {
                // Adjust by a percentage
                $value += ($value/100)*$config->calibrate_value;
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
