<?php

declare(strict_types=1);

namespace App\Filament\Resources\FiguresResource\Pages;

use App\Filament\Resources\FiguresResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFigures extends EditRecord
{
    #[\Override]
    protected static string $resource = FiguresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
