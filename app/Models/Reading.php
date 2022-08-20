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

    protected $dates = [
        'timestamp'
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
            $builder->with(['data']);
        });

        static::retrieved(function ($reading) {
            foreach($reading->data as $datum) {
                $reading->{$datum->name} = $datum->value;
            }
        });
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
