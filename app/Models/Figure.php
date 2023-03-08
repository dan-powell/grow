<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Device;
use App\Models\Datum;

class Figure extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'figure';

    public $timestamps = false;

    protected $fillable = ['name', 'key', 'summary', 'location', 'calibrate', 'calibrate_value', 'calibrate_percentage'];

    protected $appends = [
        'last_reading',
        // 'last_reading_range_value',
    ];

    protected function lastReading(): Attribute
    {
        $this->loadMissing('data');
        return Attribute::get(
            fn () => $this->data?->sortByDesc('timestamp')->first(),
        );
    }

    protected function lastReadingRangeValue(): Attribute
    {
        return Attribute::get(
            function () {
                if(isset($this->range_min) && isset($this->range_max) && $this->last_reading) {
                    if ($this->last_reading->value < $this->range_min) {
                        return 0;
                    }
                    if ($this->last_reading->value > $this->range_max) {
                        return 100;
                    }
                    $range = $this->range_max - $this->range_min;
                    return 100 / $this->range_max * ($this->last_reading->value - $this->range_min);
                }
                return null;
            }
        );
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function data()
    {
        return $this->hasMany(Datum::class);
    }

}
