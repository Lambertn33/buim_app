<?php

namespace App\Filament\Resources\PendingStockDeviceResource\Pages;

use App\Filament\Resources\PendingStockDeviceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\StockDevice;

class ManagePendingStockDevices extends ManageRecords
{
    protected static string $resource = PendingStockDeviceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(Auth::user()->role->role == Role::MANUFACTURER_ROLE),
        ];
    }

    protected function getTableQuery(): Builder
    {
        if (Auth::user()->role->role == Role::MANUFACTURER_ROLE) {
            return StockDevice::with('model')->where('is_approved', false)->where('initialized_by', Auth::user()->manufacturer->id);
        } else {
            return StockDevice::with('model')->where('is_approved', false);
        }
    }
}
