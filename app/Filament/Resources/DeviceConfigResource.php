<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceConfigResource\Pages;
use App\Filament\Resources\DeviceConfigResource\RelationManagers;
use App\Models\DeviceConfig;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeviceConfigResource extends Resource
{
    protected static ?string $model = DeviceConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('device_id')
                    ->relationship('device', 'name')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),
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
                Forms\Components\TextInput::make('location')
                    ->maxLength(255),
                Forms\Components\TextInput::make('prefix')
                    ->maxLength(255),
                Forms\Components\TextInput::make('suffix')
                    ->maxLength(255),
                Forms\Components\Toggle::make('calibrate')
                    ->required(),
                Forms\Components\TextInput::make('calibrate_value'),
                Forms\Components\Toggle::make('calibrate_percentage')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('device_id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('summary'),
                Tables\Columns\TextColumn::make('chart'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('prefix'),
                Tables\Columns\TextColumn::make('suffix'),
                Tables\Columns\IconColumn::make('calibrate')
                    ->boolean(),
                Tables\Columns\TextColumn::make('calibrate_value'),
                Tables\Columns\IconColumn::make('calibrate_percentage')
                    ->boolean(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeviceConfigs::route('/'),
            'create' => Pages\CreateDeviceConfig::route('/create'),
            'edit' => Pages\EditDeviceConfig::route('/{record}/edit'),
        ];
    }
}
