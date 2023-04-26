<?php

namespace App\Filament\Resources\WarehouseDeviceRequestResource\Pages;

use App\Filament\Resources\WarehouseDeviceRequestResource;
use App\Models\WarehouseDeviceRequest;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListWarehouseDeviceRequests extends ListRecords
{
    protected static string $resource = WarehouseDeviceRequestResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->whereNot('request_status', WarehouseDeviceRequest::INITIATED);
    }
}
