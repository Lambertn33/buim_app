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
            return parent::getTableQuery()->whereHas('sender', function($query) {
                $query->where('district_id', Auth::user()->manager->district->id);
            })->orWhereHas('receiver', function($query) {
                $query->where('district_id', Auth::user()->manager->district->id);
            });
        } else {
            return parent::getTableQuery();
        }
    }

    public function mount(): void
    {
        abort_unless(Auth::user()->role->role !== Role::SECTOR_LEADER_ROLE, 403);
    }
}
