<?php

namespace App\Models;

use App\Enum\Icons;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

#[Appends([
    'last_reading',
    'icon_src',
    'icon_small_src',
    'active_alerts',
])]
/**
 * A sensor channel on a Device, matched to incoming readings by `key`. The thing it measures
 * (e.g. a plant) is a Subject, linked over time through FigureAssignment.
 */
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

    /**
     * The name to show people: the current subject's name, falling back to the figure's own name.
     */
    protected function displayName(): Attribute
    {
        return Attribute::get(fn (): string => $this->currentSubject?->name ?? $this->name);
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
        return Attribute::get(fn (): Collection => $this->currentSubject?->alerts()->whereNotNull('activated')->get() ?? collect());
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

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(FigureAssignment::class)->orderByDesc('started_at');
    }

    /**
     * The open assignment, if the figure is currently measuring a subject. A figure has at most one.
     */
    public function currentAssignment(): HasOne
    {
        return $this->hasOne(FigureAssignment::class)->whereNull('ended_at');
    }

    public function currentSubject(): HasOneThrough
    {
        return $this->hasOneThrough(Subject::class, FigureAssignment::class, 'figure_id', 'id', 'id', 'subject_id')
            ->whereNull('figure_assignment.ended_at');
    }

    /**
     * The assignment in effect at the given moment, used to attribute a reading to a subject.
     */
    public function assignmentAt(CarbonInterface|string $at): ?FigureAssignment
    {
        return $this->assignments()->at($at)->first();
    }

    /**
     * Start measuring a subject, ending this figure's current assignment and the subject's
     * current assignment (on another figure) at the same moment.
     *
     * @throws InvalidArgumentException when $at is before an assignment it would end
     */
    public function assign(Subject $subject, ?CarbonInterface $at = null): FigureAssignment
    {
        $at = FigureAssignment::normaliseTime($at ?? now());

        return DB::transaction(function () use ($subject, $at): FigureAssignment {
            $open = FigureAssignment::query()
                ->whereNull('ended_at')
                ->where(fn ($query) => $query->where('figure_id', $this->id)->orWhere('subject_id', $subject->id))
                ->get();

            if ($open->contains(fn (FigureAssignment $assignment): bool => $assignment->started_at->gt($at))) {
                throw new InvalidArgumentException('An assignment cannot start before the assignment it replaces.');
            }

            $open->each(fn (FigureAssignment $assignment) => $assignment->update(['ended_at' => $at]));
            $this->unsetRelation('currentAssignment')->unsetRelation('currentSubject');

            return $this->assignments()->create(['subject_id' => $subject->id, 'started_at' => $at]);
        });
    }

    /**
     * Stop measuring the current subject, leaving the figure unassigned.
     */
    public function endAssignment(?CarbonInterface $at = null): void
    {
        $this->currentAssignment?->update(['ended_at' => FigureAssignment::normaliseTime($at ?? now())]);
        $this->unsetRelation('currentAssignment')->unsetRelation('currentSubject');
    }

    public function data()
    {
        return $this->hasMany(Datum::class);
    }
}
