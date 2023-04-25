<?php

namespace App\Filament\Resources\MainWarehouseResource\Pages;

use App\Filament\Resources\MainWarehouseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMainWarehouse extends EditRecord
{
    protected static string $resource = MainWarehouseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}
