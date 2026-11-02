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
 */
#[Fillable([
    'subject_id',
    'enabled',
    'value',
    'lower',
    'email',
])]
#[Table(name: 'subject_alert')]
#[WithoutTimestamps]
class SubjectAlert extends Model
{
    use HasUlids;

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    protected function casts(): array
    {
        return [
            'activated' => 'datetime',
        ];
    }
}
