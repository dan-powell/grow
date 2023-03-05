<?php

namespace App\Filament\Resources\DeviceConfigResource\Pages;

use App\Filament\Resources\DeviceConfigResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeviceConfig extends EditRecord
{
    protected static string $resource = DeviceConfigResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
