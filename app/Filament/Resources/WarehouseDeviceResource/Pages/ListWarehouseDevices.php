<?php

namespace App\Filament\Resources\WarehouseDeviceResource\Pages;

use App\Filament\Resources\WarehouseDeviceResource;
use App\Models\Role;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListWarehouseDevices extends ListRecords
{
    protected static string $resource = WarehouseDeviceResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return parent::getTableQuery()->where('manager_id', Auth::user()->manager->id);
        } else {
            return parent::getTableQuery();
        }
    }
}
