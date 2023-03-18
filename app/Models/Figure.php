<?php

namespace App\Models;

use App\Enum\Icons;
use App\Models\{Datum, Device, FigureAlert};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Model};

class Figure extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'figure';

    public $timestamps = false;

    protected $fillable = ['name', 'key', 'summary', 'location', 'icon', 'icon_custom', 'icon_small', 'prefix', 'suffix', 'calibrate', 'calibrate_value', 'calibrate_percentage', 'dashboard', 'range_min', 'range_max', 'range_min_color', 'range_max_color'];

    protected $appends = [
        'last_reading',
        'icon_src',
        'icon_small_src',
    ];

    protected function label(): Attribute
    {
        $this->loadMissing('device');

        return Attribute::get(fn (): string => $this->device->name . ' - ' . $this->name);
    }

    protected function lastReading(): Attribute
    {
        $this->loadMissing('data');

        return Attribute::get(fn () => $this->data?->sortByDesc('created_at')->sortByDesc('timestamp')->first());
    }

    protected function lastReadingRangeValue(): Attribute
    {
        return Attribute::get(function () {
            if (isset($this->range_min, $this->range_max) && $this->last_reading) {
                if ($this->last_reading->value < $this->range_min) {
                    return 0;
                }
                if ($this->last_reading->value > $this->range_max) {
                    return 100;
                }

                return 100 / ($this->range_max - $this->range_min) * ($this->last_reading->value - $this->range_min);
            }

            return null;
        });
    }

    protected function iconSrc(): Attribute
    {
        return Attribute::get(function () {
            if ($this->icon_custom) {
                return asset('storage/' . $this->icon_custom);
            }
            if ($this->icon) {
                return asset(Icons::fromName($this->icon)());
            }

            return null;
        });
    }

    protected function iconSmallSrc(): Attribute
    {
        return Attribute::get(function () {
            if ($this->icon_small) {
                return asset(Icons::fromName($this->icon_small)());
            }

            return null;
        });
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function alerts()
    {
        return $this->hasMany(FigureAlert::class);
    }

    public function data()
    {
        return $this->hasMany(Datum::class);
    }
}
