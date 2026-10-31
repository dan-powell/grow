<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FigureAlertResource\Pages\CreateFigureAlert;
use App\Filament\Resources\FigureAlertResource\Pages\EditFigureAlert;
use App\Filament\Resources\FigureAlertResource\Pages\ListFigureAlerts;
use App\Models\Figure;
use App\Models\FigureAlert;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class FigureAlertResource extends Resource
{
    #[\Override]
    protected static ?string $model = FigureAlert::class;

    #[\Override]
    protected static ?string $recordTitleAttribute = 'Alerts';

    #[\Override]
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('figure_id')
                    ->relationship('figure', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Figure $record) => "{$record->label}")
                    ->preload()
                    ->required(),
                Toggle::make('enabled')
                    ->required(),
                TextInput::make('value'),
                Toggle::make('lower')
                    ->required(),
                Toggle::make('email')
                    ->required(),
                DateTimePicker::make('active')->readonly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('figure.name')->label('Figure')->sortable(),
                TextColumn::make('figure.key')->label('Key')->sortable(),
                TextColumn::make('figure.device.name')->label('Device')->sortable(),
                ToggleColumn::make('enabled')->sortable(),
                TextColumn::make('value'),
                IconColumn::make('lower')
                    ->boolean(),
                IconColumn::make('email')
                    ->boolean(),
                TextColumn::make('active')
                    ->dateTime(),
            ])
            ->filters([
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
            'index' => ListFigureAlerts::route('/'),
            'create' => CreateFigureAlert::route('/create'),
            'edit' => EditFigureAlert::route('/{record}/edit'),
        ];
    }
}
