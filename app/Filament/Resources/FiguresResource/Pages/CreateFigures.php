<?php

declare(strict_types=1);

namespace App\Filament\Resources\FiguresResource\Pages;

use App\Filament\Resources\FiguresResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFigures extends CreateRecord
{
    #[\Override]
    protected static string $resource = FiguresResource::class;
}
