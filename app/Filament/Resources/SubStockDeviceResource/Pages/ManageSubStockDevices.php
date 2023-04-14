<?php

namespace App\Filament\Resources\SubStockDeviceResource\Pages;

use App\Filament\Resources\SubStockDeviceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSubStockDevices extends ManageRecords
{
    protected static string $resource = SubStockDeviceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
