<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use App\Models\Figure;
use App\Models\Subject;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

/**
 * Rotates a figure onto a subject, ending the figure's current assignment (and the subject's
 * assignment on any other figure). Works as a figure table row action or as a header action
 * on a relation manager owned by a figure.
 */
class AssignSubjectAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'assignSubject';
    }

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Assign subject')
            ->icon(Heroicon::ArrowsRightLeft)
            ->modalDescription('Ends the current assignment of this figure, and of the chosen subject if it is on another figure.')
            ->schema([
                Select::make('subject_id')
                    ->label('Subject')
                    ->options(fn () => Subject::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('summary')
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(fn (array $data): string => Subject::create($data)->getKey()),
                DateTimePicker::make('started_at')
                    ->label('Starting')
                    ->default(now())
                    ->required(),
            ])
            ->action(function (array $data, ?Model $record, mixed $livewire): void {
                $figure = $record instanceof Figure ? $record : $livewire->getOwnerRecord();
                try {
                    $figure->assign(Subject::findOrFail($data['subject_id']), Carbon::parse($data['started_at']));
                } catch (InvalidArgumentException $exception) {
                    Notification::make()->danger()->title('Could not assign subject')->body($exception->getMessage())->send();
                    $this->halt();
                }
                Notification::make()->success()->title('Subject assigned')->send();
                if ($livewire instanceof RelationManager) {
                    $livewire->resetTable();
                }
            });
    }
}
