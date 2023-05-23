<?php

namespace App\Filament\Resources\WarehouseDeviceRequestResource\RelationManagers;

use App\Models\Role;
use App\Models\Screening;
use App\Models\Warehouse;
use App\Models\WarehouseDeviceRequest;
use App\Models\WarehouseDeviceRequestedDevice;
use App\Services\NotificationsServices;
use App\Services\StockServices;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Actions\Action as NotificationAction;

class RequestedDevicesRelationManager extends RelationManager
{
    protected static string $relationship = 'requestedDevices';

    protected static ?string $recordTitleAttribute = 'device_name';

    protected static ?string $pluralModelLabel = 'Requested Devices';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('device_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_name'),
                Tables\Columns\TextColumn::make('model.name'),
                Tables\Columns\TextColumn::make('screener_code'),
                Tables\Columns\TextColumn::make('names')
                    ->label('screener names')
                    ->formatStateUsing(fn (WarehouseDeviceRequestedDevice $record): string => $record->getScreenedPerson()->value('prospect_names')),
                Tables\Columns\TextColumn::make('date')
                    ->label('screening date')
                    ->formatStateUsing(fn (WarehouseDeviceRequestedDevice $record): string => $record->getScreenedPerson()->value('screening_date')),
                Tables\Columns\TextColumn::make('warehouseDeviceRequest.campaign.title')
                    ->visible(Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE),
                Tables\Columns\TextColumn::make('warehouseDeviceRequest.campaign.district.district')
                    ->visible(Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('Approve and Transfer devices')
                    ->color('success')
                    ->action(function (array $data, RelationManager $livewire) {
                        try {
                            foreach ($livewire->ownerRecord->requestedDevices as $requestedDevice) {
                                $screenedUser = Screening::where('prospect_code', $requestedDevice->screener_code)->first();
                                $screenedUserWarehouse = $screenedUser->leader->warehouse;
                                (new StockServices)->approveCampaignRequestedDevices($requestedDevice, $screenedUserWarehouse->id, $livewire->ownerRecord);
                            }
                            $requestId = $livewire->ownerRecord->request_id;
                            $districtToDistributeDevice = $livewire->ownerRecord->campaign->district;
                            $managers = $districtToDistributeDevice->managers()->get();
                            $title = 'Campaign Device requests approved';
                            $message = 'The campaign  ' . $livewire->ownerRecord->campaign->title . ' with request ID of ' . sprintf("%08d", $requestId) . '  which took place in your district and requested ' . $livewire->ownerRecord->requestedDevices->count() . ' has been approved
                            and the devices are being sent to respective warehouses in your district... Please confirm after receiving them';
                            $actions = [
                                NotificationAction::make('Mark as Read')
                                    ->color('primary')
                                    ->button()
                                    ->close(),

                            ];
                            foreach ($managers as $manager) {
                                (new NotificationsServices)->sendNotificationToUser($manager->user, $title, $message, $actions);
                            }
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Error')
                                ->body('No enough devices in Transit to be sent')
                                ->danger()
                                ->send();
                            return;
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Campaign devices transfers')
                    ->modalSubheading('you are about to transfer all devices, not that all devices will be sent to the warehouses based on screening leaders')
                    ->modalButton('transfer device')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(function (RelationManager $livewire) {
                        return $livewire->ownerRecord->request_status !== WarehouseDeviceRequest::DELIVERED && (Auth::user()->role->role == Role::ADMIN_ROLE || Auth::user()->role->role == Role::STOCK_MANAGER_ROLE);
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Stock transfered')
                            ->body('The stock has been successfully trasferred.'),
                    ),
            ]);
    }
}
