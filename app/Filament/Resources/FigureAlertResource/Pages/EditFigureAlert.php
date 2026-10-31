<?php

declare(strict_types=1);

namespace App\Filament\Resources\FigureAlertResource\Pages;

use App\Filament\Resources\FigureAlertResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFigureAlert extends EditRecord
{
    #[\Override]
    protected static string $resource = FigureAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
