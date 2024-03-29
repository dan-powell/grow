<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use App\Models\Datum;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};

class DataRelationManager extends RelationManager
{
    protected static string $relationship = 'datum';

    protected static ?string $recordTitleAttribute = 'timestamp';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('Timestamp')
                    ->content(fn (Datum $record) => $record->timestamp->toDateTimeString()),
                Forms\Components\Placeholder::make('Raw Value')
                    ->content(fn (Datum $record) => $record->value),
                Forms\Components\Placeholder::make('Raw Value')
                    ->content(fn (Datum $record) => $record->value),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('figure.name')->label('Figure')->searchable(),
                Tables\Columns\TextColumn::make('value')->label('Figure')->sortable(),
                Tables\Columns\TextColumn::make('figure.prefix')->label('Prefix'),
                Tables\Columns\TextColumn::make('value_calibrated')->label('Calibrated Value')->sortable(),
                Tables\Columns\TextColumn::make('figure.suffix')->label('Suffix'),
                Tables\Columns\TextColumn::make('timestamp')->label('Timestamp')->sortable()->datetime(),
            ])
            ->filters([
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
