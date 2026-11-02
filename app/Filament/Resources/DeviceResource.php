<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\Pages\CreateDevice;
use App\Filament\Resources\DeviceResource\Pages\EditDevice;
use App\Filament\Resources\DeviceResource\Pages\ListDevices;
use App\Filament\Resources\DeviceResource\RelationManagers\DataRelationManager;
use App\Filament\Resources\DeviceResource\RelationManagers\FiguresRelationManager;
use App\Models\Device;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class DeviceResource extends Resource
{
    #[\Override]
    protected static ?string $model = Device::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

    #[\Override]
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description('A physical unit, such as a microcontroller, that sends readings. Each value it sends is stored against one of its figures.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->helperText('Shown on the site and in alert emails.')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nickname')
                            ->helperText('The identifier the device sends with each reading. It must match exactly; if you change it here, update the device too or its readings will be rejected.')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('summary')
                            ->helperText('A short description shown on the device card.')
                            ->maxLength(255),
                        Toggle::make('enabled')
                            ->helperText('Show this device and its figures on the site. Readings are still received while it is disabled.')
                            ->default(true)
                            ->inline(false),
                        TextInput::make('location')
                            ->helperText('Where the device is. Shown on the device card and in late-reading emails.')
                            ->maxLength(255),
                        FileUpload::make('image')
                            ->helperText('A photo shown on the device card, cropped to 4:3.')
                            ->disk('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('4:3')
                            ->imageResizeTargetWidth('1024')
                            ->imageResizeTargetHeight('768'),
                    ]),
                Section::make('Alert configuration')
                    ->description('Raises an alert when the device stops sending readings, e.g. because of a flat battery. Devices are checked hourly. For alerts on the readings themselves, add alerts to a subject.')
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
                            ->helperText('How long without a reading before the alert is raised. Checks run hourly, so an alert can arrive up to an hour later than this.')
                            ->required(fn (Get $get) => $get('alert_enabled'))
                            ->columnSpan(2),
                        Toggle::make('alert_email')
                            ->label('Send Emails')
                            ->helperText('Email users with "Receive alerts" on. Alerts are always logged.')
                            ->columnSpan(1)
                            ->inline(false),
                        DateTimePicker::make('alert_activated')
                            ->label('Alert Triggered')
                            ->helperText('When the current late-reading alert was raised. Cleared when the next reading arrives.')
                            ->columnSpan(2)
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('Devices send readings to the API, identified by their nickname. Drag rows to change the display order.')
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('summary')->sortable(),
                TextColumn::make('last_reading.created_at')
                    ->dateTime(),
                ToggleColumn::make('enabled'),
                ToggleColumn::make('alert_enabled'),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
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
