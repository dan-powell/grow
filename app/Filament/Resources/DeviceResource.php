<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\{Pages, RelationManagers};
use App\Models\Device;
use Closure;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

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
                        Forms\Components\FileUpload::make('image')
                            ->disk('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('4:3')
                            ->imageResizeTargetWidth('1024')
                            ->imageResizeTargetHeight('768'),
                    ]),
                Forms\Components\Section::make('Alert configuration')
                    ->columns(6)
                    ->schema([
                        Forms\Components\Toggle::make('alert_enabled')
                            ->label('Enabled')
                            ->reactive()
                            ->columnSpan(1)
                            ->inline(false),
                        Forms\Components\TextInput::make('alert_timeout')
                            ->label('Alert Timeout')
                            ->suffix('mins')
                            ->required(fn (Device $device, \Filament\Forms\Get $get) => $get('alert_enabled'))
                            ->columnSpan(2),
                        Forms\Components\Toggle::make('alert_email')
                            ->label('Send Emails')
                            ->columnSpan(1)
                            ->inline(false),
                        Forms\Components\DateTimePicker::make('alert_activated')
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
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('summary'),
                Tables\Columns\TextColumn::make('last_reading.created_at')
                    ->dateTime(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
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
