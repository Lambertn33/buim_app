<?php

namespace App\Filament\Resources\PendingStockDeviceResource\Pages;

use App\Filament\Resources\PendingStockDeviceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\StockDevice;
use App\Models\StockModel;
use App\Services\StockServices;
use Illuminate\Support\Str;
use Filament\Pages\Actions\Action;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ManagePendingStockDevices extends ManageRecords
{
    protected static string $resource = PendingStockDeviceResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('download excel sample')
                ->visible(Auth::user()->role->role === Role::MANUFACTURER_ROLE)
                ->action('downloadStockExcelFormat')
                ->requiresConfirmation()
                ->modalSubheading('please fill this downloaded file and upload it')
                ->color('danger')
                ->modalButton('download sample'),
            ImportAction::make()
                ->visible(Auth::user()->role->role === Role::MANUFACTURER_ROLE)
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
                    $data['is_approved'] = false;
                    $data['initialized_by'] = Auth::user()->manufacturer->id;
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                    return StockDevice::create($data);
                })
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

    public function mount(): void
    {
        abort_unless(Auth::user()->role->role == Role::MANUFACTURER_ROLE ||
            Auth::user()->role->role == Role::ADMIN_ROLE ||
            Auth::user()->role->role == Role::STOCK_MANAGER_ROLE, 403);
    }
    public function downloadStockExcelFormat()
    {
        return (new StockServices)->getSampleExcel();
    }
}
