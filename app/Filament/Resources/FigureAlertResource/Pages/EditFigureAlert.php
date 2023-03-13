<?php

namespace App\Filament\Resources\FigureAlertResource\Pages;

use App\Filament\Resources\FigureAlertResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFigureAlert extends EditRecord
{
    protected static string $resource = FigureAlertResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
