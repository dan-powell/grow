<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enum\Severity;
use App\Filament\Resources\LogResource;
use App\Models\Log;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestLog extends TableWidget
{
    #[\Override]
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest log entries')
            ->query(Log::query()->with('device')->latest()->limit(8))
            ->headerActions([
                Action::make('viewAll')
                    ->label('View all')
                    ->link()
                    ->url(LogResource::getUrl('index')),
            ])
            ->columns([
                TextColumn::make('name')
                    ->description(fn (Log $record): ?string => $record->summary),
                TextColumn::make('severity')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        Severity::WARNING->value => 'warning',
                        Severity::SUCCESS->value => 'success',
                        default => 'info',
                    }),
                TextColumn::make('device.name')->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('When')
                    ->since()
                    ->dateTimeTooltip(),
            ])
            ->paginated(false);
    }
}
