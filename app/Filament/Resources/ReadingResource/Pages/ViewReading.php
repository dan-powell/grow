<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReadingResource\Pages;

use App\Filament\Resources\ReadingResource;
use Filament\Resources\Pages\ViewRecord;

class ViewReading extends ViewRecord
{
    #[\Override]
    protected static string $resource = ReadingResource::class;
}
