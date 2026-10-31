<?php

namespace App\Filament\Resources;

use App\Enum\Icons;
use App\Filament\Resources\FiguresResource\Pages\CreateFigures;
use App\Filament\Resources\FiguresResource\Pages\EditFigures;
use App\Filament\Resources\FiguresResource\Pages\ListFigures;
use App\Models\Figure;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class FiguresResource extends Resource
{
    #[\Override]
    protected static ?string $model = Figure::class;

    #[\Override]
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('device_id')
                    ->relationship('device', 'name')
                    ->required(),
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('key')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('summary')
                            ->maxLength(255),
                        TextInput::make('chart')
                            ->maxLength(255),
                        Select::make('icon')
                            ->options(array_combine(Icons::names(), Icons::names())),
                        FileUpload::make('icon_custom'),
                        Select::make('icon_small')
                            ->options(array_combine(Icons::names(), Icons::names())),
                        TextInput::make('location')
                            ->maxLength(255),
                    ]),
                Section::make('Data Configuration')
                    ->columns(2)
                    ->schema([
                        TextInput::make('prefix')
                            ->maxLength(255),
                        TextInput::make('suffix')
                            ->maxLength(255),
                        Section::make('Calibration')
                            ->columns(6)
                            ->schema([
                                Toggle::make('calibrate')
                                    ->label('Enabled')
                                    ->required()
                                    ->reactive()
                                    ->inline(false)
                                    ->columnSpan(1),
                                Toggle::make('calibrate_percentage')
                                    ->label('Percentage?')
                                    ->inline(false)
                                    ->columnSpan(1),
                                TextInput::make('calibrate_value')
                                    ->label('Value')
                                    ->hint('The amount to adjust raw value by')
                                    ->required(fn (Figure $figure, Get $get) => $get('calibrate'))
                                    ->numeric()
                                    ->columnSpan(4),
                            ]),
                        Section::make('Range Bar Chart')
                            ->columns(2)
                            ->schema([
                                TextInput::make('range_min')
                                    ->label('Min')
                                    ->lt('range_max')
                                    ->numeric(),
                                TextInput::make('range_max')
                                    ->label('Max')
                                    ->gt('range_min')
                                    ->numeric(),
                                ColorPicker::make('range_min_color')
                                    ->rgb()
                                    ->label('Min Color'),
                                ColorPicker::make('range_max_color')
                                    ->rgb()
                                    ->label('Max Color'),
                            ]),
                    ]),
                Section::make()
                    ->schema([
                        Toggle::make('dashboard')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('key')->sortable()->searchable(),
                TextColumn::make('summary')->sortable()->searchable(),
                TextColumn::make('device.name')->sortable()->searchable(),
                ToggleColumn::make('dashboard'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->defaultSort('order')
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFigures::route('/'),
            'create' => CreateFigures::route('/create'),
            'edit' => EditFigures::route('/{record}/edit'),
        ];
    }
}
