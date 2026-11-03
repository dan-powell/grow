<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\SubjectResource;
use App\Models\SubjectAlert;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class ActiveAlerts extends TableWidget
{
    #[\Override]
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Active alerts')
            ->description('Subject alerts raised by a reading and not yet resolved.')
            ->query(SubjectAlert::query()
                ->with('subject.currentAssignment.figure')
                ->where('enabled', true)
                ->whereNotNull('activated')
                ->orderByDesc('activated'))
            ->recordUrl(fn (SubjectAlert $record): string => SubjectResource::getUrl('edit', ['record' => $record->subject_id]))
            ->columns([
                TextColumn::make('subject.name')->label('Subject'),
                TextColumn::make('subject.currentAssignment.figure.name')->label('Figure')->placeholder('Unassigned'),
                TextColumn::make('condition')
                    ->state(fn (SubjectAlert $record): string => ($record->lower ? 'Below ' : 'Above ').$record->value),
                TextColumn::make('activated')
                    ->label('Raised')
                    ->since()
                    ->dateTimeTooltip(),
            ])
            ->emptyStateHeading('No active alerts')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated(false);
    }
}
