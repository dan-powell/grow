<?php

namespace App\Models;

use App\Enum\Icons;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Appends([
    'last_reading',
    'icon_src',
    'icon_small_src',
    'active_alerts',
])]
#[Fillable(['name', 'key', 'summary', 'location', 'icon', 'icon_custom', 'icon_small', 'prefix', 'suffix', 'calibrate', 'calibrate_value', 'calibrate_percentage', 'dashboard', 'range_min', 'range_max', 'range_min_color', 'range_max_color', 'order'])]
#[Table(name: 'figure')]
#[WithoutTimestamps]
class Figure extends Model
{
    use HasFactory;
    use HasUlids;

    protected function label(): Attribute
    {
        $this->loadMissing('device');

        return Attribute::get(fn (): string => $this->device->name.' - '.$this->name.' - '.$this->key);
    }

    protected function lastReading(): Attribute
    {
        return Attribute::get(fn () => $this->data()->orderBy('created_at', 'desc')->orderBy('timestamp', 'desc')->limit(1)->first());
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

    protected function activeAlerts(): Attribute
    {
        return Attribute::get(fn () => $this->alerts()->whereNotNull('activated')->get());
    }

    protected function iconSrc(): Attribute
    {
        return Attribute::get(function (): string {
            if ($this->icon_custom) {
                return asset('storage/'.$this->icon_custom);
            }
            if ($this->icon) {
                return asset(Icons::fromName($this->icon)());
            }

            // Default to sprout icon
            return asset(Icons::fromName('SPROUT')());
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

    #[Scope]
    protected function dashboard($query)
    {
        $query->where('dashboard', true);
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
