<?php

namespace App\Filament\Resources\WarehouseDeviceTransferResource\Pages;

use App\Filament\Resources\WarehouseDeviceTransferResource;
use App\Models\Role;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ManageWarehouseDeviceTransfers extends ManageRecords
{
    protected static string $resource = WarehouseDeviceTransferResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTableQuery(): Builder
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            $authenticatedManager = Auth::user()->manager;
            return parent::getTableQuery()->where('manager_sender_id', $authenticatedManager->id)
                ->orWhere('manager_receiver_id', $authenticatedManager->id);
        } else {
            return parent::getTableQuery();
        }
    }
}
