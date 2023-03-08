<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use App\Models\Datum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataRelationManager extends RelationManager
{
    protected static string $relationship = 'data';

    protected static ?string $recordTitleAttribute = 'timestamp';

    public static function form(Form $form): Form
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

    public static function table(Table $table): Table
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
                //
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
