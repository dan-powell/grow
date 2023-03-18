<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasManyThrough};
use Illuminate\Database\Eloquent\{Builder, Model};
use App\Models\{Datum};

class Device extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'device';

    public $timestamps = true;

    protected $fillable = ['name', 'nickname', 'image', 'summary', 'location', 'alert_enabled', 'alert_timeout', 'alert_email'];

    protected $casts = [
        'alert_activated' => 'datetime',
    ];

    protected $appends = [
        'last_reading',
        'image_src',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Always eager-load data
        // static::addGlobalScope('eager', function (Builder $builder) {
        //     $builder->with(['figures.data.figure']);
        // });
    }

    protected function lastReading(): Attribute
    {
        return Attribute::get(function () {
            return $this->data()->orderBy('created_at', 'desc')->orderBy('timestamp', 'desc')->first();
        });
    }

    protected function imageSrc(): Attribute
    {
        return Attribute::get(fn () => $this->image ? asset('storage/' . $this->image) : null);
    }

    public function scopeDashboard($query)
    {
        $query->whereHas('figures', function ($query) {
            $query->where('dashboard', true);
        });
    }

    public function figures(): HasMany
    {
        return $this->hasMany(Figure::class);
    }

    public function data(): HasManyThrough
    {
        return $this->through('figures')->has('data');
    }
}
