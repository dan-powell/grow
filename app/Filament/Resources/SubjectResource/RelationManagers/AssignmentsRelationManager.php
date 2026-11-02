<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubjectResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Read-only history of the figures that have measured a subject.
 * Assignments are managed from the figure.
 */
class AssignmentsRelationManager extends RelationManager
{
    #[\Override]
    protected static string $relationship = 'assignments';

    #[\Override]
    protected static ?string $title = 'Figures';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('figure.name')->label('Figure'),
                TextColumn::make('figure.device.name')->label('Device'),
                TextColumn::make('started_at')->dateTime()->sortable(),
                TextColumn::make('ended_at')->dateTime()->sortable()->placeholder('Current'),
            ])
            ->defaultSort('started_at', 'desc');
    }
}
