<?php

declare(strict_types=1);

namespace App\Filament\Resources\FigureAlertResource\Pages;

use App\Filament\Resources\FigureAlertResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFigureAlert extends CreateRecord
{
    #[\Override]
    protected static string $resource = FigureAlertResource::class;
}
