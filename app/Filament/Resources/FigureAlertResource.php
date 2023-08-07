<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FigureAlertResource\{Pages, RelationManagers};
use App\Models\{Figure, FigureAlert};
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};

class FigureAlertResource extends Resource
{
    protected static ?string $model = FigureAlert::class;

    protected static ?string $recordTitleAttribute = 'Alerts';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('figure_id')
                    ->relationship('figure', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Figure $record) => "{$record->label}")
                    ->preload()
                    ->required(),
                Forms\Components\Toggle::make('enabled')
                    ->required(),
                Forms\Components\TextInput::make('value'),
                Forms\Components\Toggle::make('lower')
                    ->required(),
                Forms\Components\Toggle::make('email')
                    ->required(),
                Forms\Components\DateTimePicker::make('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('figure.name'),
                Tables\Columns\IconColumn::make('enabled')
                    ->boolean(),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\IconColumn::make('lower')
                    ->boolean(),
                Tables\Columns\IconColumn::make('email')
                    ->boolean(),
                Tables\Columns\TextColumn::make('active')
                    ->dateTime(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFigureAlerts::route('/'),
            'create' => Pages\CreateFigureAlert::route('/create'),
            'edit' => Pages\EditFigureAlert::route('/{record}/edit'),
        ];
    }
}
