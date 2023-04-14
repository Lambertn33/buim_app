<?php

namespace App\Filament\Resources\PendingStockDeviceResource\Pages;

use App\Filament\Resources\PendingStockDeviceResource;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\StockDevice;
use App\Models\StockModel;
use App\Services\StockServices;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Pages\Actions\Action;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;
use Filament\Notifications\Notification;

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
                ->label('Import excel')
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
                }),
            Action::make('approve stock')
                ->hidden(Auth::user()->role->role === Role::MANUFACTURER_ROLE)
                ->requiresConfirmation()
                ->modalSubheading('Before selecting the initialisation code,
                    please review the pending devices with such initialisation code that you want to approve')
                ->modalButton('approve stock')
                ->form([
                    Select::make('initialization_code')
                        ->required()
                        ->label('Initialization code')
                        ->placeholder('Select initialization code')
                        ->options(StockDevice::where('is_approved', false)->distinct()->pluck('initialization_code', 'initialization_code')->toArray())
                ])
                ->action(function(array $data): void {
                    $initializationCode = $data['initialization_code'];
                    (new StockServices)->updateStockDeviceInitialization($initializationCode);
                })->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Stock updated')
                        ->body('The stock has been successfully updated.'),
                ),
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
