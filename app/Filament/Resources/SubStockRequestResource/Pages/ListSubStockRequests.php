<?php

namespace App\Filament\Resources\SubStockRequestResource\Pages;

use App\Filament\Resources\SubStockRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubStockRequests extends ListRecords
{
    protected static string $resource = SubStockRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
