<?php

namespace App\Filament\Resources\StockDeviceResource\Pages;

use App\Filament\Resources\StockDeviceResource;
use App\Models\Role;
use App\Models\StockDevice;
use App\Models\StockModel;
use App\Services\StockServices;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ManageStockDevices extends ManageRecords
{
    protected static string $resource = StockDeviceResource::class;

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
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Device registered')
                        ->body('New device has been successfully created.'),
                ),
            Action::make('download excel sample')
                ->action('downloadStockExcelFormat')
                ->requiresConfirmation()
                ->modalSubheading('please fill this downloaded file and upload it')
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
                    $data['initialization_code'] = 'ST-'.$now.'';
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
                    return StockDevice::create($data);
                })
        ];
    }

    public function downloadStockExcelFormat()
    {
        return (new StockServices)->getSampleExcel();
    }
}
