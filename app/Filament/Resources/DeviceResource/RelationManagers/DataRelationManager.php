<?php

declare(strict_types=1);

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use App\Models\Datum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DataRelationManager extends RelationManager
{
    #[\Override]
    protected static string $relationship = 'datum';

    #[\Override]
    protected static ?string $recordTitleAttribute = 'timestamp';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('Timestamp')
                    ->content(fn (Datum $record) => $record->timestamp->toDateTimeString()),
                Placeholder::make('Raw Value')
                    ->content(fn (Datum $record) => $record->value),
                Placeholder::make('Raw Value')
                    ->content(fn (Datum $record) => $record->value),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('figure.name')->label('Figure')->searchable(),
                TextColumn::make('value')->label('Figure')->sortable(),
                TextColumn::make('figure.prefix')->label('Prefix'),
                TextColumn::make('value_calibrated')->label('Calibrated Value')->sortable(),
                TextColumn::make('figure.suffix')->label('Suffix'),
                TextColumn::make('timestamp')->label('Timestamp')->sortable()->datetime(),
            ])
            ->filters([
            ])
            ->headerActions([
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
            ]);
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'timestamp';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
