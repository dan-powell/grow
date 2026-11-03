<?php

declare(strict_types=1);

namespace App\Filament\Resources\FiguresResource\RelationManagers;

use App\Filament\Actions\AssignSubjectAction;
use App\Models\FigureAssignment;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * The history of subjects measured by a figure. New assignments are made with
 * AssignSubjectAction; editing is for correcting dates.
 */
class AssignmentsRelationManager extends RelationManager
{
    #[\Override]
    protected static string $relationship = 'assignments';

    #[\Override]
    protected static ?string $title = 'Subjects';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('started_at')
                    ->helperText('When this figure started measuring the subject.')
                    ->required()
                    ->rules([
                        fn (Get $get, ?FigureAssignment $record): Closure => function (string $attribute, $value, Closure $fail) use ($get, $record): void {
                            $candidate = clone $record;
                            $candidate->started_at = $value;
                            $candidate->ended_at = $get('ended_at') ?: null;
                            if ($candidate->overlapsExisting()) {
                                $fail('This period overlaps another assignment for this figure or subject.');
                            }
                        },
                    ]),
                DateTimePicker::make('ended_at')
                    ->after('started_at')
                    ->helperText('Leave empty while the subject is still being measured.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('The subjects this figure has measured. Each reading belongs to the subject assigned when it was taken, so correcting dates here also changes which subject past readings belong to.')
            ->columns([
                TextColumn::make('subject.name')->label('Subject'),
                TextColumn::make('started_at')->dateTime()->sortable(),
                TextColumn::make('ended_at')->dateTime()->sortable()->placeholder('Current'),
            ])
            ->defaultSort('started_at', 'desc')
            ->headerActions([
                AssignSubjectAction::make(),
            ])
            ->recordActions([
                Action::make('end')
                    ->icon(Heroicon::Stop)
                    ->visible(fn (FigureAssignment $record): bool => $record->ended_at === null)
                    ->schema([
                        DateTimePicker::make('ended_at')
                            ->label('Ending')
                            ->default(now())
                            ->after(fn (FigureAssignment $record) => $record->started_at)
                            ->required(),
                    ])
                    ->action(fn (array $data, FigureAssignment $record) => $record->update(['ended_at' => $data['ended_at']])),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
