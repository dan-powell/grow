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
        'value_calibrated',
        'value_string',
        'range_percentage',
        'range_color',
        'timestamp_formatted',
        'timestamp_date',
        'timestamp_time',
        'timestamp_diff',
        'created_at_formatted',
        'created_at_date',
        'created_at_time',
        'created_at_diff',
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

                return 100 / $this->figure->range_max * ($this->value_calibrated - $this->figure->range_min);
            }

            return null;
        });
    }

    /**
     * Take two RGB colour strings and mix them together
     *
     * @param string $color_1
     * @param string $color_2
     * @param float $weight
     *
     * @return string
     */
    private function mix($color_1 = 'rgb(0, 0, 0)', $color_2 = 'rgb(255, 255, 255)', $weight = 0.5): string
    {
        $color_1 = sscanf($color_1, 'rgb(%d, %d, %d)');
        $color_2 = sscanf($color_2, 'rgb(%d, %d, %d)');

        $f = function ($x) use ($weight) {
            return $weight * $x;
        };

        $g = function ($x) use ($weight) {
            return (1 - $weight) * $x;
        };

        $h = function ($x, $y) {
            return round($x + $y);
        };

        return 'rgb(' . implode(',', array_map($h, array_map($f, $color_1), array_map($g, $color_2))) . ')';
    }

    protected function rangeColor(): Attribute
    {
        return Attribute::get(function (): string {
            $default = 'rgb(144, 163, 90)';
            $tint = 'rgb(255, 255, 255)';
            $shade = 'rgb(255, 255, 255)';
            if ($this->figure->range_min_color && $this->figure->range_max_color) {
                return $this->mix($this->figure->range_max_color, $this->figure->range_min_color, $this->range_percentage / 100);
            }
            if (!$this->figure->range_min_color && $this->figure->range_max_color) {
                return $this->mix($shade, $this->figure->range_max_color, $this->range_percentage / 100);
            }
            if ($this->figure->range_min_color && !$this->figure->range_max_color) {
                return $this->mix($this->figure->range_min_color, $tint, $this->range_percentage / 100);
            }

            return $default;
        });
    }

    protected function valueCalibrated(): Attribute
    {
        return Attribute::get(fn () => $this->calibrateDataValue($this->figure, $this->value));
    }

    protected function valueString(): Attribute
    {
        return Attribute::get(fn (): string => $this->figure->prefix . $this->value_calibrated . $this->figure->suffix);
    }

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::get(fn (): string => $this->created_at->toDateTimeString());
    }

    protected function createdAtDate(): Attribute
    {
        return Attribute::get(fn (): string => $this->created_at->format('jS M o'));
    }

    protected function createdAtTime(): Attribute
    {
        return Attribute::get(fn (): string => $this->created_at->format('h:ia'));
    }

    protected function createdAtDiff(): Attribute
    {
        return Attribute::get(fn (): string => $this->created_at->diffForHumans());
    }

    protected function timestampFormatted(): Attribute
    {
        return Attribute::get(fn (): string => $this->timestamp->toDateTimeString());
    }

    protected function timestampDate(): Attribute
    {
        return Attribute::get(fn (): string => $this->timestamp->format('jS M o'));
    }

    protected function timestampTime(): Attribute
    {
        return Attribute::get(fn (): string => $this->timestamp->format('h:ia'));
    }

    protected function timestampDiff(): Attribute
    {
        return Attribute::get(fn (): string => $this->timestamp->diffForHumans());
    }

    public function figure()
    {
        return $this->belongsTo(FigureParent::class, 'figure_id');
    }
}
