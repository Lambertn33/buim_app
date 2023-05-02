<?php

namespace App\Filament\Resources\WarehouseDeviceResource\Pages;

use App\Filament\Resources\WarehouseDeviceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarehouseDevice extends EditRecord
{
    protected static string $resource = WarehouseDeviceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
