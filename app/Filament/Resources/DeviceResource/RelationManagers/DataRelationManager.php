<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use App\Models\Datum;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DataRelationManager extends RelationManager
{
    #[\Override]
    protected static string $relationship = 'datum';

    #[\Override]
    protected static ?string $recordTitleAttribute = 'timestamp';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
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
