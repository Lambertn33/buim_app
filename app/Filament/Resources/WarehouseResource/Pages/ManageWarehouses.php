<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\WarehouseResource;
use App\Models\District;
use App\Models\Role;
use App\Models\Warehouse;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ManageWarehouses extends ManageRecords
{
    protected static string $resource = WarehouseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(Auth::user()->role->role == Role::ADMIN_ROLE || Auth::user()->role->role == Role::STOCK_MANAGER_ROLE)
                ->mutateFormDataUsing(function (array $data): array {
                    $data['id'] = Str::uuid()->toString();
                    return $data;
                })
        ];
    }

    public function getTableQuery(): Builder
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return parent::getTableQuery()->whereHas('district', function ($query) {
                $query->where('id', Auth::user()->manager->district->id);
            });
        } else if (Auth::user()->role->role === Role::SECTOR_LEADER_ROLE) {
            return parent::getTableQuery()->where('id', Auth::user()->leader->warehouse_id);
        } else {
            return parent::getTableQuery();
        }
    }
}
