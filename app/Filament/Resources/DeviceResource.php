<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\Pages\CreateDevice;
use App\Filament\Resources\DeviceResource\Pages\EditDevice;
use App\Filament\Resources\DeviceResource\Pages\ListDevices;
use App\Filament\Resources\DeviceResource\RelationManagers\DataRelationManager;
use App\Filament\Resources\DeviceResource\RelationManagers\FiguresRelationManager;
use App\Models\Device;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class DeviceResource extends Resource
{
    #[\Override]
    protected static ?string $model = Device::class;

    #[\Override]
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nickname')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('summary')
                            ->maxLength(255),
                        TextInput::make('location')
                            ->maxLength(255),
                        FileUpload::make('image')
                            ->disk('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('4:3')
                            ->imageResizeTargetWidth('1024')
                            ->imageResizeTargetHeight('768'),
                    ]),
                Section::make('Alert configuration')
                    ->columns(6)
                    ->schema([
                        Toggle::make('alert_enabled')
                            ->label('Enabled')
                            ->reactive()
                            ->columnSpan(1)
                            ->inline(false),
                        TextInput::make('alert_timeout')
                            ->label('Alert Timeout')
                            ->suffix('mins')
                            ->required(fn (Device $device, Get $get) => $get('alert_enabled'))
                            ->columnSpan(2),
                        Toggle::make('alert_email')
                            ->label('Send Emails')
                            ->columnSpan(1)
                            ->inline(false),
                        DateTimePicker::make('alert_activated')
                            ->label('Alert Triggered')
                            ->columnSpan(2)
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('summary')->sortable(),
                TextColumn::make('last_reading.created_at')
                    ->dateTime(),
                ToggleColumn::make('alert_enabled'),
            ])
            ->defaultSort('order')
            ->reorderable('order')
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
            FiguresRelationManager::class,
            DataRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDevices::route('/'),
            'create' => CreateDevice::route('/create'),
            'edit' => EditDevice::route('/{record}/edit'),
        ];
    }
}
