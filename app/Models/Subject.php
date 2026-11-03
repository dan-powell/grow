<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\Icons;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * The thing a Figure measures, such as a plant. Subjects come and go from sensors
 * over time; see FigureAssignment.
 */
#[Appends(['image_src', 'icon_src'])]
#[Fillable(['name', 'summary', 'image', 'icon', 'icon_custom', 'notes'])]
#[Table(name: 'subject')]
class Subject extends Model
{
    use HasFactory;
    use HasUlids;

    protected function imageSrc(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->image ? asset('storage/'.$this->image) : null);
    }

    protected function iconSrc(): Attribute
    {
        return Attribute::get(fn (): ?string => Icons::src($this->icon, $this->icon_custom));
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(FigureAssignment::class)->orderByDesc('started_at');
    }

    /**
     * The open assignment, if the subject is currently being measured. A subject has at most one.
     */
    public function currentAssignment(): HasOne
    {
        return $this->hasOne(FigureAssignment::class)->whereNull('ended_at');
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(SubjectAlert::class);
    }
}
