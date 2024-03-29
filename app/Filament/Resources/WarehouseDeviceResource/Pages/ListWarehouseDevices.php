<?php

namespace App\Filament\Resources\WarehouseDeviceResource\Pages;

use App\Filament\Resources\WarehouseDeviceResource;
use App\Models\Role;
use App\Models\Warehouse;
use App\Services\StockServices;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListWarehouseDevices extends ListRecords
{
    protected static string $resource = WarehouseDeviceResource::class;

    protected $listeners = [
        'approveDistrictIncomingDevice' => 'approveDistrictIncomingDeviceListener',
        'rejectDistrictIncomingDevice' => 'rejectDistrictIncomingDeviceListener'
    ];

    public function approveDistrictIncomingDeviceListener($warehouse, $warehouseId, $device)
    {
        (new StockServices)->approveDistrictIncomingDeviceListener($warehouse, $warehouseId, $device);
    }

    public function rejectDistrictIncomingDeviceListener($device, $deviceReceiver, $deviceSender)
    {
        (new StockServices)->rejectDistrictIncomingDeviceListener($device, $deviceReceiver, $deviceSender);
    }

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return parent::getTableQuery()->where('district_id', Auth::user()->manager->district->id)->whereHas('warehouse', function ($query) {
                $query->where('status', Warehouse::ACTIVE);
            });
        } else if(Auth::user()->role->role === Role::SECTOR_LEADER_ROLE) {
            return parent::getTableQuery()->where('district_id', Auth::user()->leader->district->id)->whereHas('warehouse', function ($query) {
                $query->where('status', Warehouse::ACTIVE)->where('warehouse_id', Auth::user()->leader->warehouse_id);
            });
        } else {
            return parent::getTableQuery();
        }
    }
}
