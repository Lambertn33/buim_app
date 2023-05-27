<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RugandoMainWarehouseResource\Pages;
use App\Filament\Resources\RugandoMainWarehouseResource\RelationManagers;
use App\Models\MainWarehouse;
use App\Models\RugandoMainWarehouse;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use App\Models\MainWarehouseDevice;
use App\Models\Role;
use App\Models\Warehouse;
use App\Services\NavigationBadgesServices;
use App\Services\NotificationsServices;
use App\Services\StockServices;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RugandoMainWarehouseResource extends Resource
{
    protected static ?string $model = MainWarehouseDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationLabel = 'Transit';

    protected static ?string $navigationGroup = 'Inventory - Main warehouses';

    protected static ?string $slug = 'rugando-warehouse';

    protected static ?string $modelLabel = 'Transit Warehouse Devices';

    protected static ?int $navigationSort = 4;

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role->role === Role::ADMIN_ROLE ||
            Auth::user()->role->role === Role::STOCK_MANAGER_ROLE;
    }

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfRugandoWarehouseDevices();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('device_name')
                    ->required()
                    ->label('device name'),
                Select::make('model_id')
                    ->required()
                    ->placeholder('select model')
                    ->label('device model')
                    ->relationship('model', 'name'),
                TextInput::make('serial_number')
                    ->required()
                    ->unique(ignoreRecord: true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device_name')
                    ->sortable()
                    ->searchable()
                    ->label('device name'),
                TextColumn::make('device_price')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state): string => $state !== null ? ("{$state} FRWS") : '-')
                    ->label('Device price'),
                TextColumn::make('model.name')
                    ->sortable()
                    ->searchable()
                    ->label('device model'),
                TextColumn::make('serial_number')
                    ->sortable()
                    ->searchable()
                    ->label('serial Number'),
            ])
            ->filters([
                SelectFilter::make('device_name')
                    ->label('Filter by Device name')
                    ->options(MainWarehouseDevice::orderBy('device_name', 'asc')->where('is_approved', true)->distinct()->pluck('device_name', 'device_name')->toArray())
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('transfer')
                    ->color('success')
                    ->action(function (MainWarehouseDevice $record, array $data) {
                        try {
                            (new StockServices)->transferMainWarehouseDevice($record, $data['warehouse_id'], $data['warehouse_type']);
                            if ($data['warehouse_type'] == 'District warehouse') {
                                $districtWarehouse = Warehouse::with('district')->find($data['warehouse_id']);
                                $managers = $districtWarehouse->district->managers()->get();
                                $title = 'New Received device';
                                $message = 'a new device with serial number ' . $record->serial_number . ' has been sent to your warehouse ';
                                $actions = [
                                    NotificationAction::make('Mark as Read')
                                        ->color('primary')
                                        ->button()
                                        ->close(),

                                ];
                                foreach($managers as $manager) {
                                    (new NotificationsServices)->sendNotificationToUser($manager->user, $title, $message, $actions);
                                }
                            }
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Error')
                                ->body('an error occured... please try again')
                                ->danger()
                                ->send();
                            return;
                        }
                    })
                    ->requiresConfirmation()
                    ->modalSubheading('select other main warehouse to transfer this device')
                    ->modalButton('transfer device')
                    ->icon('heroicon-o-paper-airplane')
                    ->label('Transfer')
                    ->form(fn ($record) => [
                        Select::make('warehouse_type')
                            ->label('warehouse type')
                            ->required()
                            ->placeholder('select warehouse type')
                            ->reactive()
                            ->options([
                                'Main warehouse' => 'Main warehouse',
                                'District warehouse' => 'District warehouse'
                            ]),
                        Select::make('warehouse_id')
                            ->required()
                            ->label('Destination warehouse')
                            ->placeholder('select warehouse')
                            ->searchable()
                            ->options(function (callable $get, $record) {
                                $warehouseType = $get('warehouse_type');
                                if ($warehouseType) {
                                    if ($warehouseType == 'Main warehouse') {
                                        $dpWarehouse = MainWarehouse::where('name', MainWarehouse::DPWORLDWAREHOUSE)->first();
                                        return MainWarehouse::whereNot('id', $dpWarehouse->id)->get()->pluck('name', 'id')->toArray();
                                    } else {
                                        return Warehouse::where('status', Warehouse::ACTIVE)->get()->pluck('name', 'id')->toArray();
                                    }
                                }
                            })
                            ->visible(function (callable $get) {
                                $warehouseType = $get('warehouse_type');
                                if ($warehouseType) {
                                    return true;
                                }
                            })
                    ])
                    ->successNotification(
                        Notification::make('success')
                            ->title('Device transfered')
                            ->body('device has been successfully transfered.'),
                    )
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('transfer selected')
                    ->color('success')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->modalSubheading('select other main warehouse to transfer selected devices')
                    ->modalButton('transfer selected devices')
                    ->form([
                        Select::make('warehouse_type')
                            ->label('warehouse type')
                            ->required()
                            ->placeholder('select warehouse type')
                            ->reactive()
                            ->options([
                                'Main warehouse' => 'Main warehouse',
                                'District warehouse' => 'District warehouse'
                            ]),
                        Select::make('warehouse_id')
                            ->required()
                            ->label('District warehouse')
                            ->placeholder('select warehouse')
                            ->searchable()
                            ->options(function (callable $get) {
                                $warehouseType = $get('warehouse_type');
                                if ($warehouseType) {
                                    if ($warehouseType == 'Main warehouse') {
                                        $dpWarehouse = MainWarehouse::where('name', MainWarehouse::DPWORLDWAREHOUSE)->first();
                                        return MainWarehouse::whereNot('id', $dpWarehouse->id)->get()->pluck('name', 'id')->toArray();
                                    } else {
                                        return Warehouse::where('status', Warehouse::ACTIVE)->get()->pluck('name', 'id')->toArray();
                                    }
                                }
                            })
                            ->visible(function (callable $get) {
                                $warehouseType = $get('warehouse_type');
                                if ($warehouseType) {
                                    return true;
                                }
                            })
                    ])
                    ->action(
                        function (Collection $records, array $data) {
                            foreach ($records as $record) {
                                (new StockServices)->transferMainWarehouseDevice($record, $data['warehouse_id'], $data['warehouse_type']);
                            }
                        }
                    )->successNotification(
                        Notification::make('success')
                            ->title('Device transfered')
                            ->body('device has been successfully transfered.'),
                    )
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRugandoMainWarehouses::route('/'),
        ];
    }
}
