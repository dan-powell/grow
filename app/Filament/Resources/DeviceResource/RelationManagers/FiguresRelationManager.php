<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use App\Models\Figure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FiguresRelationManager extends RelationManager
{
    protected static string $relationship = 'figures';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('device_id')
                    ->relationship('device', 'name')
                    ->required(),
                Forms\Components\Card::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('summary')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('chart')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('icon'),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Data Configuration')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('prefix')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('suffix')
                            ->maxLength(255),
                        Forms\Components\Section::make('Calibration')
                            ->columns(6)
                            ->schema([
                                Forms\Components\Toggle::make('calibrate')
                                    ->label('Enabled')
                                    ->required()
                                    ->inline(false)
                                    ->columnSpan(1),
                                Forms\Components\Toggle::make('calibrate_percentage')
                                    ->label('Percentage?')
                                    ->required()
                                    ->inline(false)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('calibrate_value')
                                    ->label('Value')
                                    ->hint('The amount to adjust raw value by')
                                    ->numeric()
                                    ->columnSpan(4),
                            ]),
                        Forms\Components\Section::make('Range Bar Chart')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('range_min')
                                    ->label('Min')
                                    ->lt('range_max')
                                    ->numeric()
                                    ,
                                Forms\Components\TextInput::make('range_max')
                                    ->label('Max')
                                    ->gt('range_min')
                                    ->numeric(),
                                Forms\Components\ColorPicker::make('range_min_color')
                                    ->label('Min Color'),
                                Forms\Components\ColorPicker::make('range_max_color')
                                    ->label('Max Color'),
                            ])
                        ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Toggle::make('dashboard')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_reading.created_at')->datetime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
