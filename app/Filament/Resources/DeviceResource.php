<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\{Pages, RelationManagers};
use App\Models\Device;
use Closure;
use Filament\Resources\{Form, Resource, Table};
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-o-chip';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nickname')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('summary')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('image'),
                    ]),
                Forms\Components\Section::make('Alert configuration')
                    ->columns(5)
                    ->schema([
                        Forms\Components\Toggle::make('reading_alert')
                            ->label('Enabled')
                            ->reactive()
                            ->columnSpan(1)
                            ->inline(false),
                        Forms\Components\TextInput::make('reading_alert_timeout')
                            ->label('Alert Timeout')
                            ->suffix('mins')
                            ->required(fn (Device $device, Closure $get) => $get('reading_alert'))
                            ->columnSpan(2),
                        Forms\Components\DateTimePicker::make('reading_alert_last')
                            ->label('Last Alert')
                            ->columnSpan(2)
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('summary'),
                Tables\Columns\TextColumn::make('last_reading.created_at')
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
            DeviceResource\RelationManagers\FiguresRelationManager::class,
            DeviceResource\RelationManagers\DataRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevices::route('/'),
            'create' => Pages\CreateDevice::route('/create'),
            'edit' => Pages\EditDevice::route('/{record}/edit'),
        ];
    }
}
