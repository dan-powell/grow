<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datum extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'datum';

    public $timestamps = true;

    protected $fillable = ['name', 'value'];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    protected $appends = [
        'timestamp_formatted',
        'value_calibrated',
        'range_percentage',
    ];

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

    protected function rangePercentage(): Attribute
    {
        return Attribute::get(function () {
                if (isset($this->figure->range_min, $this->figure->range_max)) {
                    if ($this->value_calibrated < $this->figure->range_min) {
                        return 0;
                    }
                    if ($this->value_calibrated > $this->figure->range_max) {
                        return 100;
                    }
                    $range = $this->figure->range_max - $this->figure->range_min;

                    return 100 / $this->figure->range_max * ($this->value_calibrated - $this->figure->range_min);
                }

                return null;
            });
    }

    protected function valueCalibrated(): Attribute
    {
        return Attribute::get(fn () => $this->calibrateDataValue($this->figure, $this->value));
    }

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::get(fn () => $this->created_at->toDateTimeString());
    }

    protected function timestampFormatted(): Attribute
    {
        return Attribute::get(fn () => $this->timestamp->toDateTimeString());
    }

    public function figure()
    {
        return $this->belongsTo(FigureParent::class, 'figure_id');
    }
}
