<?php

namespace App\Filament\Resources\WarehouseDeviceRequestResource\Pages;

use App\Filament\Resources\WarehouseDeviceRequestResource;
use App\Models\Role;
use App\Models\WarehouseDeviceRequest;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::user()->role->role == Role::DISTRICT_MANAGER_ROLE) {
            return parent::getTableQuery()->whereNot('request_status', WarehouseDeviceRequest::INITIATED)->whereHas('campaign', function($query){
                $query->where('manager_id', Auth::user()->manager->id);
            });
        } else {
            return parent::getTableQuery()->whereNot('request_status', WarehouseDeviceRequest::INITIATED);
        }
    }
}
