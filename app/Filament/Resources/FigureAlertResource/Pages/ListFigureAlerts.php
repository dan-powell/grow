<?php

declare(strict_types=1);

namespace App\Filament\Resources\FigureAlertResource\Pages;

use App\Filament\Resources\FigureAlertResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFigureAlerts extends ListRecords
{
    #[\Override]
    protected static string $resource = FigureAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
