<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

#[Appends([
    'last_reading',
    'image_src',
    'alerted',
    'alert_activated_formatted',
])]
#[Hidden(['latestDatum'])]
#[Fillable(['name', 'nickname', 'enabled', 'image', 'summary', 'location', 'alert_enabled', 'alert_timeout', 'alert_email', 'order'])]
#[Table(name: 'device')]
class Device extends Model
{
    use HasFactory;
    use HasUlids;

    #[\Override]
    public $timestamps = true;

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

    /**
     * The most recently received reading across all of the device's figures. Eager-load
     * `latestDatum` to avoid a query per device; the relation itself is hidden so the reading
     * is only serialised once, under this name.
     */
    protected function lastReading(): Attribute
    {
        return Attribute::get(fn (): ?Datum => $this->latestDatum);
    }

    protected function imageSrc(): Attribute
    {
        return Attribute::get(fn () => $this->image ? asset('storage/'.$this->image) : null);
    }

    protected function alerted(): Attribute
    {
        return Attribute::get(fn (): bool => $this->alert_enabled && $this->alert_activated);
    }

    protected function alertActivatedFormatted(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->alert_activated?->toDateTimeString());
    }

    /**
     * Devices shown on the site. Disabled devices still receive readings.
     */
    #[Scope]
    protected function enabled($query)
    {
        $query->where('enabled', true);
    }

    #[Scope]
    protected function dashboard($query)
    {
        $query->whereHas('figures', function ($query) {
            $query->where('dashboard', true);
        });
    }

    public function figures(): HasMany
    {
        return $this->hasMany(Figure::class)->orderBy('order');
    }

    public function latestDatum(): HasOneThrough
    {
        return $this->hasOneThrough(Datum::class, Figure::class)->latestOfMany('created_at');
    }

    public function data()
    {
        return $this->figures()->whereHas('data', function ($query) {
            $query->orderBy('created_at', 'desc')->orderBy('timestamp', 'desc')->limit(1);
        });
    }

    public function datum()
    {
        return $this->through('figures')->has('data');
    }

    protected function casts(): array
    {
        return [
            'alert_activated' => 'datetime',
            'enabled' => 'boolean',
        ];
    }
}
