<?php

namespace App\Filament\Resources\WarehouseDeviceRequestResource\Pages;

use App\Filament\Resources\WarehouseDeviceRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarehouseDeviceRequest extends EditRecord
{
    protected static string $resource = WarehouseDeviceRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
