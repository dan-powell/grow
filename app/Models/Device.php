<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Appends([
    'last_reading',
    'image_src',
    'alerted',
    'alert_activated_formatted',
])]
#[Fillable(['name', 'nickname', 'image', 'summary', 'location', 'alert_enabled', 'alert_timeout', 'alert_email', 'order'])]
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

    protected function lastReading(): Attribute
    {
        return Attribute::get(fn () => $this->figures()->whereHas('data', function ($query) {
            $query->orderBy('created_at', 'desc')->orderBy('timestamp', 'desc')->limit(1);
        })->limit(1)->get()->first()?->last_reading);
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
        ];
    }
}
