<?php

namespace App\Filament\Resources\DPWorldMainWarehouseResource\Pages;

use App\Filament\Resources\DPWorldMainWarehouseResource;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Builder;
use App\Models\MainWarehouseDevice;
use App\Models\MainWarehouse;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ManageRecords;

class ManageDPWorldMainWarehouses extends ManageRecords
{
    protected static string $resource = DPWorldMainWarehouseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create device')
                ->mutateFormDataUsing(function (array $data): array {
                    $now = now()->format('dmy');
                    $randomNumber = rand(10000, 99999);
                    $initializationCode = 'ST-' . $now . '-' . $randomNumber . '';
                    $data['id'] = Str::uuid()->toString();
                    $data['initialization_code'] = $initializationCode;
                    $data['main_warehouse_id'] = MainWarehouse::where('name', MainWarehouse::DPWORLDWAREHOUSE)->value('id');
                    $data['is_approved'] = true;
                    // use elseif not else for in the future there might be another role which will have acess
                    if (Auth::user()->role->role == Role::ADMIN_ROLE) {
                        $data['initialized_by'] = Auth::user()->id;
                        $data['approved_by'] = Auth::user()->id;
                    } elseif (Auth::user()->role->role == Role::STOCK_MANAGER_ROLE) {
                        $data['initialized_by'] = Auth::user()->stockManager->id;
                        $data['approved_by'] = Auth::user()->stockManager->id;
                    }
                    return $data;
                }),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return MainWarehouseDevice::whereHas('mainWarehouse', function($query){
            $query->where('name', MainWarehouse::DPWORLDWAREHOUSE);
        });
    }

    public function mount(): void
    {
        abort_unless(Auth::user()->role->role == Role::ADMIN_ROLE ||
            Auth::user()->role->role == Role::MANUFACTURER_ROLE ||
            Auth::user()->role->role == Role::STOCK_MANAGER_ROLE, 403);
    }
}
