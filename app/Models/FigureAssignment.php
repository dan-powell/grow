<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A period during which a Figure measured a Subject. `ended_at` is null while the
 * assignment is current. A figure and a subject each have at most one open assignment,
 * and assignments for the same figure or subject never overlap.
 */
#[Fillable(['figure_id', 'subject_id', 'started_at', 'ended_at'])]
#[Table(name: 'figure_assignment')]
class FigureAssignment extends Model
{
    use HasFactory;
    use HasUlids;

    public function figure(): BelongsTo
    {
        return $this->belongsTo(Figure::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Assignments that were in effect at the given moment.
     */
    #[Scope]
    protected function at(Builder $query, CarbonInterface|string $at): void
    {
        $at = self::normaliseTime($at);

        $query->where('started_at', '<=', $at)
            ->where(fn (Builder $query) => $query->whereNull('ended_at')->orWhere('ended_at', '>', $at));
    }

    /**
     * Whether this assignment's period overlaps another assignment for the same figure or subject.
     */
    public function overlapsExisting(): bool
    {
        return self::query()
            ->when($this->exists, fn (Builder $query) => $query->whereKeyNot($this->getKey()))
            ->where(fn (Builder $query) => $query->where('figure_id', $this->figure_id)->orWhere('subject_id', $this->subject_id))
            ->where(fn (Builder $query) => $query->whereNull('ended_at')->orWhere('ended_at', '>', $this->started_at))
            ->when($this->ended_at, fn (Builder $query) => $query->where('started_at', '<', $this->ended_at))
            ->exists();
    }

    /**
     * Datetimes are stored without a zone, so compare in the application's timezone.
     */
    public static function normaliseTime(CarbonInterface|string $at): Carbon
    {
        return Carbon::parse($at)->setTimezone(config('app.timezone'));
    }

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }
}
