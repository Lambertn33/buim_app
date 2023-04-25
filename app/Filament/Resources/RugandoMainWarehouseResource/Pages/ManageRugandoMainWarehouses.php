<?php

namespace App\Filament\Resources\RugandoMainWarehouseResource\Pages;

use App\Filament\Resources\RugandoMainWarehouseResource;
use App\Models\MainWarehouse;
use App\Models\MainWarehouseDevice;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ManageRugandoMainWarehouses extends ManageRecords
{
    protected static string $resource = RugandoMainWarehouseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create device')
                ->modalSubheading('please fill this downloaded file and upload it')
                ->mutateFormDataUsing(function (array $data): array {
                    $now = now()->format('dmy');
                    $randomNumber = rand(10000, 99999);
                    $initializationCode = 'ST-' . $now . '-' . $randomNumber . '';
                    $data['id'] = Str::uuid()->toString();
                    $data['initialization_code'] = $initializationCode;
                    $data['main_warehouse_id'] = MainWarehouse::where('name', MainWarehouse::RUGANDOWAREHOUSE)->value('id');
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
            $query->where('name', MainWarehouse::RUGANDOWAREHOUSE);
        });
    }

    public function mount(): void
    {
        abort_unless(Auth::user()->role->role == Role::ADMIN_ROLE ||
            Auth::user()->role->role == Role::STOCK_MANAGER_ROLE, 403);
    }
}
