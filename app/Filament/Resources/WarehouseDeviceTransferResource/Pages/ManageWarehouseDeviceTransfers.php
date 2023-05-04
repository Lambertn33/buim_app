<?php

namespace App\Filament\Resources\WarehouseDeviceTransferResource\Pages;

use App\Filament\Resources\WarehouseDeviceTransferResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWarehouseDeviceTransfers extends ManageRecords
{
    protected static string $resource = WarehouseDeviceTransferResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
