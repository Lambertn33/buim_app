<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\WarehouseResource;
use App\Models\District;
use App\Models\Role;
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
                $districtManagerId = null;
                if (!is_null(District::where('id', $data['district_id'])->value('manager_id'))) {
                    $districtManagerId = District::where('id', $data['district_id'])->value('manager_id');
                }
                $data['manager_id'] = $districtManagerId;
                return $data;
            })
        ];
    }

    public function getTableQuery(): Builder
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return parent::getTableQuery()->where('manager_id', Auth::user()->manager->id);
        } else {
            return parent::getTableQuery();
        }
    }
}
