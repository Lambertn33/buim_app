<?php

namespace App\Filament\Resources\MainWarehouseDeviceResource\Pages;

use App\Filament\Resources\MainWarehouseDeviceResource;
use App\Models\MainWarehouse;
use App\Models\MainWarehouseDevice;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\StockModel;
use App\Models\Warehouse;
use App\Services\StockServices;
use Filament\Forms\Components\Select;

class ManageMainWarehouseDevices extends ManageRecords
{
    protected static string $resource = MainWarehouseDeviceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->hidden(Auth::user()->role->role == Role::MANUFACTURER_ROLE)
                ->label('Create device')
                ->modalSubheading('please fill this downloaded file and upload it, initially all imported devices are stored in DP World Main warehouse')
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
            Action::make('download excel sample')
                ->action('downloadStockExcelFormat')
                ->requiresConfirmation()
                ->modalSubheading('please fill this downloaded file and upload it, initially all imported devices are stored in DP World Main warehouse')
                ->color('danger')
                ->modalButton('download sample'),
            ImportAction::make()
                ->handleBlankRows(true)
                ->fields([
                    ImportField::make('device_name')
                        ->required()
                        ->label('device name'),
                    ImportField::make('serial_number')
                        ->required()
                        ->label('serial number'),
                    ImportField::make('model')
                        ->mutateBeforeCreate(fn ($value) => StockModel::where('name', 'LIKE', "%{$value}%")->value('id'))
                        ->label('model')
                        ->required()
                ])->handleRecordCreation(function ($data) {
                    $now = now()->format('dmyhis');
                    $data['id'] = Str::uuid()->toString();
                    $data['model_id'] = $data['model'];
                    $data['initialization_code'] = 'ST-' . $now . '';
                    $data['main_warehouse_id'] = MainWarehouse::where('name', MainWarehouse::DPWORLDWAREHOUSE)->value('id');
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                    if (Auth::user()->role->role === Role::ADMIN_ROLE || Auth::user()->role->role === Role::STOCK_MANAGER_ROLE) {
                        $data['is_approved'] = true;
                        $data['initialized_by'] = Auth::user()->role->role === Role::ADMIN_ROLE ?
                            Auth::user()->id
                            : Auth::user()->stockManager->id;
                        $data['approved_by'] = Auth::user()->role->role === Role::ADMIN_ROLE ?
                            Auth::user()->id
                            : Auth::user()->stockManager->id;
                    } else {
                        $data['is_approved'] = false;
                        $data['initialized_by'] = Auth::user()->manufacturer->id;
                    }
                    return MainWarehouseDevice::create($data);
                })
        ];
    }

    public function downloadStockExcelFormat()
    {
        return (new StockServices)->getSampleExcel();
    }
}
