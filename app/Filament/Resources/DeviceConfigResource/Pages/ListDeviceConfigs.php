<?php

namespace App\Filament\Resources\DeviceConfigResource\Pages;

use App\Filament\Resources\DeviceConfigResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeviceConfigs extends ListRecords
{
    protected static string $resource = DeviceConfigResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
