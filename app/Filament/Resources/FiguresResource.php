<?php

namespace App\Filament\Resources;

use App\Enum\Icons;
use App\Filament\Resources\FiguresResource\Pages;
use App\Filament\Resources\FiguresResource\RelationManagers;
use App\Models\Figure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FiguresResource extends Resource
{
    protected static ?string $model = Figure::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('device_id')
                    ->relationship('device', 'name')
                    ->required(),
                Forms\Components\Section::make()
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
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Toggle::make('dashboard')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nickname')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('summary')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('device.name')->sortable()->searchable(),
                Tables\Columns\ToggleColumn::make('dashboard'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListFigures::route('/'),
            'create' => Pages\CreateFigures::route('/create'),
            'edit' => Pages\EditFigures::route('/{record}/edit'),
        ];
    }
}
