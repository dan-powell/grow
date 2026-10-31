<?php

declare(strict_types=1);

namespace App\Filament\Resources\FiguresResource\Pages;

use App\Filament\Resources\FiguresResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFigures extends ListRecords
{
    #[\Override]
    protected static string $resource = FiguresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
