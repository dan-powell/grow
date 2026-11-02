<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class DeviceStatus extends TableWidget
{
    #[\Override]
    protected static ?int $sort = 2;

    #[\Override]
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Devices')
            ->description('When each device last sent a reading. Late devices have passed their alert timeout.')
            ->query(Device::query()->withMax('datum', 'datum.created_at')->orderBy('order'))
            ->recordUrl(fn (Device $record): string => DeviceResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('location')->placeholder('—'),
                IconColumn::make('enabled')->boolean(),
                TextColumn::make('datum_max_datum_created_at')
                    ->label('Last reading')
                    ->since()
                    ->dateTimeTooltip()
                    ->placeholder('Never'),
                TextColumn::make('status')
                    ->state(fn (Device $record): string => match (true) {
                        ! $record->alert_enabled => 'Not monitored',
                        $record->alert_activated !== null => 'Late',
                        default => 'OK',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Late' => 'danger',
                        'OK' => 'success',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}
