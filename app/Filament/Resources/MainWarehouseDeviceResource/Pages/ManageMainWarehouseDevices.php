<?php

namespace App\Filament\Resources\MainWarehouseDeviceResource\Pages;

use App\Filament\Resources\MainWarehouseDeviceResource;
use App\Models\MainWarehouse;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Services\StockServices;

class ManageMainWarehouseDevices extends ManageRecords
{
    protected static string $resource = MainWarehouseDeviceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->hidden(Auth::user()->role->role == Role::MANUFACTURER_ROLE)
            ->label('Create device')
            ->mutateFormDataUsing(function (array $data): array {
                $now = now()->format('dmy');
                $randomNumber = rand(10000, 99999);
                $initializationCode = 'ST-'.$now.'-'.$randomNumber.'';
                $data['id'] = Str::uuid()->toString();
                $data['initialization_code'] = $initializationCode;
                // $data['main_warehouse_id'] = MainWarehouse::where('name', MainWarehouse::DPWORLDWAREHOUSE)->value('id');
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
            Action::make('download excel sample')
                ->action('downloadStockExcelFormat')
                ->requiresConfirmation()
                ->modalSubheading('please fill this downloaded file and upload it')
                ->color('danger')
                ->modalButton('download sample'),
        ];
    }

    public function downloadStockExcelFormat()
    {
        return (new StockServices)->getSampleExcel();
    }
}
