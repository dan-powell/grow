<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A threshold alert on a Subject, checked against readings from whichever
 * Figure the subject was assigned to when the reading was taken.
 *
 * Once raised, the alert only resolves when a reading comes back past the threshold by
 * `resolve_margin`, so a value hovering around the threshold doesn't flap.
 */
#[Fillable([
    'subject_id',
    'enabled',
    'value',
    'lower',
    'resolve_margin',
    'email',
])]
#[Table(name: 'subject_alert')]
#[WithoutTimestamps]
class SubjectAlert extends Model
{
    use HasUlids;

    /**
     * Whether a calibrated reading crosses the threshold and should raise the alert.
     */
    public function isTrippedBy(float $value): bool
    {
        if ($this->value === null) {
            return false;
        }

        return $this->lower ? $value < $this->value : $value > $this->value;
    }

    /**
     * Whether a calibrated reading is far enough back past the threshold to resolve the alert.
     */
    public function isResolvedBy(float $value): bool
    {
        if ($this->value === null) {
            return false;
        }

        $margin = abs((float) $this->resolve_margin);

        return $this->lower ? $value > $this->value + $margin : $value < $this->value - $margin;
    }

    /**
     * A sentence describing how far a calibrated reading has crossed the threshold.
     */
    public function breachSummary(float $value): string
    {
        return 'The reading value "'.$value.'" has '.($this->lower ? 'subceeded' : 'exceeded').' the alert by '.round(abs($value - (float) $this->value), 2);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'lower' => 'boolean',
            'activated' => 'datetime',
        ];
    }
}
