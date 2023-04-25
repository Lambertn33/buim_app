<?php

namespace App\Filament\Resources\DPWorldMainWarehouseResource\Pages;

use App\Filament\Resources\DPWorldMainWarehouseResource;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Builder;
use App\Models\MainWarehouseDevice;
use App\Models\MainWarehouse;
use App\Models\Role;
use Filament\Pages\Actions\Action;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;
use App\Models\StockModel;
use App\Services\StockServices;
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
            Action::make('download excel sample')
                ->action('downloadStockExcelFormat')
                ->requiresConfirmation()
                ->modalSubheading('please fill this downloaded file and upload it, initially all imported devices are stored in DP World Main warehouse')
                ->color('danger')
                ->modalButton('download sample'),
            ImportAction::make()
                ->handleBlankRows(true)
                ->label('Import initial stock')
                ->modalSubheading('This is the initial stock before being transfered to different warehouses')
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

    protected function getTableQuery(): Builder
    {
        if (Auth::user()->role->role == Role::MANUFACTURER_ROLE) {
            $manufacturerId = Auth::user()->manufacturer->id;
            return MainWarehouseDevice::where('initialized_by', $manufacturerId)->whereHas('mainWarehouse', function ($query) {
                $query->where('name', MainWarehouse::DPWORLDWAREHOUSE);
            });
        } else {
            return MainWarehouseDevice::whereHas('mainWarehouse', function ($query) {
                $query->where('name', MainWarehouse::DPWORLDWAREHOUSE);
            });
        }
    }

    public function mount(): void
    {
        abort_unless(Auth::user()->role->role == Role::ADMIN_ROLE ||
            Auth::user()->role->role == Role::MANUFACTURER_ROLE ||
            Auth::user()->role->role == Role::STOCK_MANAGER_ROLE, 403);
    }

    public function downloadStockExcelFormat()
    {
        return (new StockServices)->getSampleExcel();
    }
}
