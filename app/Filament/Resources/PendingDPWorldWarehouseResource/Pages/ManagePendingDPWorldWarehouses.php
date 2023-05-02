<?php

namespace App\Filament\Resources\PendingDPWorldWarehouseResource\Pages;

use App\Filament\Resources\PendingDPWorldWarehouseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\MainWarehouseDevice;
use App\Models\MainWarehouse;
use App\Models\StockModel;
use App\Models\User;
use App\Services\NotificationsServices;
use Illuminate\Support\Str;
use App\Services\StockServices;
use Filament\Forms\Components\Select;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ManagePendingDPWorldWarehouses extends ManageRecords
{
    protected static string $resource = PendingDPWorldWarehouseResource::class;

    public $initializationCode;
    public $now;

    public function __construct()
    {
        $this->now = now()->format('dmyhi');
        $this->initializationCode = 'ST-' . $this->now . '';
    }


    protected function getActions(): array
    {
        return [
            Action::make('download excel sample')
                ->action('downloadStockExcelFormat')
                ->requiresConfirmation()
                ->modalSubheading('please fill this downloaded file and upload it, initially all imported devices are stored in DP World Main warehouse')
                ->color('danger')
                ->visible(Auth::user()->role->role == Role::MANUFACTURER_ROLE)
                ->modalButton('download sample'),
            ImportAction::make()
                ->handleBlankRows(true)
                ->visible(Auth::user()->role->role == Role::MANUFACTURER_ROLE)
                ->label('Import stock')
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
                    $data['id'] = Str::uuid()->toString();
                    $data['model_id'] = $data['model'];
                    $data['initialization_code'] = 'ST-' . $this->now . '';
                    $data['main_warehouse_id'] = MainWarehouse::where('name', MainWarehouse::DPWORLDWAREHOUSE)->value('id');
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                    $data['is_approved'] = false;
                    $data['initialized_by'] = Auth::user()->manufacturer->id;
                    return MainWarehouseDevice::create($data);
                })->after(function () {
                    $users =  User::whereHas('role', function ($query) {
                        $query->where('role', Role::STOCK_MANAGER_ROLE)
                            ->orWhere('role', Role::ADMIN_ROLE);
                    })->get();
                    $title = 'New stock # ' . $this->initializationCode . '';
                    $message = 'a new stock with code ' . $this->initializationCode . ' has been initialized by '
                        . Auth::user()->name . ' ';
                    $this->sendNotificationOnStockInitialization($users, $title, $message);
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
                        ->options(MainWarehouseDevice::where('is_approved', false)->distinct()->pluck('initialization_code', 'initialization_code')->toArray())
                ])
                ->action(function (array $data): void {
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

    public function sendNotificationOnStockInitialization($users, $title, $message)
    {
        foreach ($users as $user) {
            (new NotificationsServices)->sendNotificationToUser($user, $title, $message);
        }
    }

    protected function getTableQuery(): Builder
    {
        if (Auth::user()->role->role == Role::MANUFACTURER_ROLE) {
            $manufacturerId = Auth::user()->manufacturer->id;
            return MainWarehouseDevice::where('initialized_by', $manufacturerId)->where('is_approved', false)->whereHas('mainWarehouse', function ($query) {
                $query->where('name', MainWarehouse::DPWORLDWAREHOUSE);
            });
        } else {
            return MainWarehouseDevice::where('is_approved', false)->whereHas('mainWarehouse', function ($query) {
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
