<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseDeviceResource\Pages;
use App\Filament\Resources\WarehouseDeviceResource\RelationManagers;
use App\Models\District;
use App\Models\Role;
use App\Models\StockModel;
use App\Models\WarehouseDevice;
use App\Models\Warehouse;
use App\Services\NavigationBadgesServices;
use App\Services\StockServices;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class WarehouseDeviceResource extends Resource
{
    protected static ?string $model = WarehouseDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Inventory - District warehouses';

    protected static ?string $navigationLabel = 'Warehouse devices';

    protected static ?string $pluralModelLabel = 'District Warehouse Devices';

    protected static ?int $navigationSort = 3;

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfWarehouseDevices();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device_name')
                    ->label('Device name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('model.name')
                    ->label('Device model')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('warehouse.district.district')
                    ->visible(Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE),
                TextColumn::make('warehouse.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('serial_number')
                    ->sortable()
                    ->searchable()
                    ->label('Serial number'),
                TextColumn::make('Device Availability')
                    ->sortable()
                    ->weight('bold')
                    ->formatStateUsing(function(WarehouseDevice $record): string {
                        return (is_null($record->screener_id)) ? 'Available' : 'Distributed';
                    })->color(function(WarehouseDevice $record): string {
                        return (is_null($record->screener_id)) ? 'success' : 'danger';
                    })
            ])
            ->filters([
                SelectFilter::make('district_id')
                    ->label('Filter by District')
                    ->searchable()
                    ->visible(Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE)
                    ->options(District::orderBy('district', 'asc')->get()->pluck('district', 'id')->toArray()),
                SelectFilter::make('model_id')
                    ->label('Filter by device models')
                    ->searchable()
                    ->options(StockModel::get()->pluck('name', 'id')->toArray())

            ])
            ->actions([
                Tables\Actions\Action::make('transfer')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalSubheading('select district and warehouse to transfer this device')
                    ->modalButton('transfer device')
                    ->icon('heroicon-o-paper-airplane')
                    ->label('Transfer')
                    ->form([
                        Select::make('district_id')
                            ->required()
                            ->placeholder('select district')
                            ->label('District')
                            ->reactive()
                            ->options(function ($record) {
                                return District::has('warehouses', '>', 0)->get()->pluck('district', 'id')->toArray();
                            }),
                        Select::make('warehouse_id')
                            ->required()
                            ->label('District warehouse')
                            ->placeholder('select warehouse')
                            ->searchable()
                            ->options(function (callable $get, WarehouseDevice $record) {
                                $district = District::find($get('district_id'));
                                return Warehouse::where('district_id', $district->id)->whereNot('id', $record->warehouse_id)->get()->pluck('name', 'id')->toArray();
                            })
                            ->visible(function (callable $get) {
                                $district = $get('district_id');
                                if ($district) {
                                    return true;
                                }
                            }),
                        Textarea::make('reason')
                            ->required()
                    ])
                    ->action(function (WarehouseDevice $record, array $data) {
                        (new StockServices)->transferDistrictWarehouseDevice($record, $data);
                    })
                    ->successNotification(
                        Notification::make('success')
                            ->title('Device transfered')
                            ->body('device has been successfully transfered.'),
                    )
                    ->visible(function (WarehouseDevice $record){
                        if(Auth::user()->role->role == Role::DISTRICT_MANAGER_ROLE && is_null($record->screener_id)) {
                            return true;
                        }else {
                            return false;
                        }
                    })
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
                        Select::make('district_id')
                            ->required()
                            ->placeholder('select district')
                            ->label('District')
                            ->reactive()
                            ->options(function () {
                                return District::whereNotNull('manager_id')->whereNot('id', Auth::user()->manager->district->id)->get()->pluck('district', 'id')->toArray();
                            }),
                        Select::make('warehouse_id')
                            ->required()
                            ->label('District warehouse')
                            ->placeholder('select warehouse')
                            ->searchable()
                            ->options(function (callable $get) {
                                $district = District::find($get('district_id'));
                                return $district->warehouses()->get()->pluck('name', 'id')->toArray();
                            })
                            ->visible(function (callable $get) {
                                $district = $get('district_id');
                                if ($district) {
                                    return true;
                                }
                            }),
                        Textarea::make('reason')
                            ->required()
                    ])->visible(Auth::user()->role->role ===Role::DISTRICT_MANAGER_ROLE)
                    ->action(function (Collection $records, array $data) {
                        foreach ($records as $record) {
                            (new StockServices)->transferDistrictWarehouseDevice($record, $data);
                        }
                    })
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWarehouseDevices::route('/'),
            'create' => Pages\CreateWarehouseDevice::route('/create'),
            'edit' => Pages\EditWarehouseDevice::route('/{record}/edit'),
        ];
    }
}
