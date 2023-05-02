<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DPWorldMainWarehouseResource\Pages;
use App\Filament\Resources\DPWorldMainWarehouseResource\RelationManagers;
use App\Models\DPWorldMainWarehouse;
use App\Models\MainWarehouse;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use App\Models\MainWarehouseDevice;
use App\Models\Role;
use App\Models\Warehouse;
use App\Services\NavigationBadgesServices;
use App\Services\StockServices;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DPWorldMainWarehouseResource extends Resource
{
    protected static ?string $model = MainWarehouseDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationLabel = 'DP World Warehouse';

    protected static ?string $navigationGroup = 'main warehouses inventory';

    protected static ?string $slug = 'dp-world-warehouse';

    protected static ?string $modelLabel = 'DP World Warehouse Devices';

    protected static ?int $navigationSort = 2;

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role->role === Role::ADMIN_ROLE ||
            Auth::user()->role->role === Role::STOCK_MANAGER_ROLE;
    }


    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfDPWorldWarehouseDevices();
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
                TextColumn::make('model.name')
                    ->sortable()
                    ->searchable()
                    ->label('device model'),
                TextColumn::make('initialization_code')
                    ->sortable()
                    ->searchable()
                    ->label('Initialization Code'),
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
                        (new StockServices)->transferMainWarehouseDevice($record, $data['warehouse_id'], $data['warehouse_type']);
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
                            ->label('District warehouse')
                            ->placeholder('select warehouse')
                            ->searchable()
                            ->options(function(callable $get, $record){
                                $warehouseType = $get('warehouse_type');
                                if ($warehouseType) {
                                    if ($warehouseType == 'Main warehouse') {
                                       return MainWarehouse::whereNot('id', $record->main_warehouse_id)->get()->pluck('name', 'id')->toArray(); 
                                    } else {
                                        return Warehouse::get()->pluck('name', 'id')->toArray();
                                    }
                                }
                            })
                            ->visible(function(callable $get){
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
                        Select::make('main_warehouse_id')
                            ->label('Main warehouse')
                            ->required()
                            ->placeholder('select other main warehouse')
                            ->options(MainWarehouse::whereNot('name', MainWarehouse::DPWORLDWAREHOUSE)->get()->pluck('name', 'id')->toArray())
                    ])
                    ->action(fn (Collection $records, array $data) => $records->each->update([
                        'main_warehouse_id' => $data['main_warehouse_id']
                    ]))->successNotification(
                        Notification::make('success')
                            ->title('Device transfered')
                            ->body('device has been successfully transfered.'),
                    )
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDPWorldMainWarehouses::route('/'),
        ];
    }
}
