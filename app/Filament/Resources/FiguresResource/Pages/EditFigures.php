<?php

namespace App\Filament\Resources\FiguresResource\Pages;

use App\Filament\Resources\FiguresResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFigures extends EditRecord
{
    protected static string $resource = FiguresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
