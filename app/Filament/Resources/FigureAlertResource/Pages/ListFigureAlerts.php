<?php

namespace App\Filament\Resources\FigureAlertResource\Pages;

use App\Filament\Resources\FigureAlertResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFigureAlerts extends ListRecords
{
    protected static string $resource = FigureAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
