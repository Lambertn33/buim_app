<?php

namespace App\Filament\Resources\MainWarehouseResource\Pages;

use App\Filament\Resources\MainWarehouseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMainWarehouses extends ListRecords
{
    protected static string $resource = MainWarehouseResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
