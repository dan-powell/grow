<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use App\Enum\Icons;
use App\Models\Figure;
use Closure;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};

class FiguresRelationManager extends RelationManager
{
    protected static string $relationship = 'figures';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
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
                        Forms\Components\Select::make('icon')
                            ->options(array_combine(Icons::names(), Icons::names())),
                        Forms\Components\FileUpload::make('icon_custom'),
                        Forms\Components\Select::make('icon_small')
                            ->options(array_combine(Icons::names(), Icons::names())),
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
                                    ->reactive()
                                    ->inline(false)
                                    ->columnSpan(1),
                                Forms\Components\Toggle::make('calibrate_percentage')
                                    ->label('Percentage?')
                                    ->inline(false)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('calibrate_value')
                                    ->label('Value')
                                    ->hint('The amount to adjust raw value by')
                                    ->required(fn (Figure $figure, \Filament\Forms\Get $get) => $get('calibrate'))
                                    ->numeric()
                                    ->columnSpan(4),
                            ]),
                        Forms\Components\Section::make('Range Bar Chart')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('range_min')
                                    ->label('Min')
                                    ->lt('range_max')
                                    ->numeric(),
                                Forms\Components\TextInput::make('range_max')
                                    ->label('Max')
                                    ->gt('range_min')
                                    ->numeric(),
                                Forms\Components\ColorPicker::make('range_min_color')
                                    ->rgb()
                                    ->label('Min Color'),
                                Forms\Components\ColorPicker::make('range_max_color')
                                    ->rgb()
                                    ->label('Max Color'),
                            ]),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Toggle::make('dashboard')
                            ->required(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('summary')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('key')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_reading.created_at')->datetime(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
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

    protected function getTableReorderColumn(): ?string
    {
        return 'order';
    }
}
