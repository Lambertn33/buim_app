<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HQMainWarehouseResource\Pages;
use App\Filament\Resources\HQMainWarehouseResource\RelationManagers;
use App\Models\HQMainWarehouse;
use App\Models\MainWarehouse;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use App\Models\MainWarehouseDevice;
use App\Models\Role;
use App\Services\NavigationBadgesServices;
use App\Services\StockServices;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class HQMainWarehouseResource extends Resource
{
    protected static ?string $model = MainWarehouseDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationLabel = 'HQ Warehouse';

    protected static ?string $navigationGroup = 'main warehouses inventory';

    protected static ?string $slug = 'hq-warehouse';

    protected static ?string $modelLabel = 'HQ Warehouse Devices';

    protected static ?int $navigationSort = 3;

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfHQWarehouseDevices();
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role->role === Role::ADMIN_ROLE ||
            Auth::user()->role->role === Role::STOCK_MANAGER_ROLE;
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
                TextColumn::make('serial_number')
                    ->sortable()
                    ->searchable()
                    ->label('serial Number'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('transfer')
                    ->color('success')
                    ->action(function (MainWarehouseDevice $record, array $data) {
                        (new StockServices)->transferMainWarehouseDevice($record, $data['main_warehouse_id']);
                    })
                    ->requiresConfirmation()
                    ->modalSubheading('select other main warehouse to transfer this device')
                    ->modalButton('transfer device')
                    ->icon('heroicon-o-paper-airplane')
                    ->label('Transfer')
                    ->form(fn ($record) => [
                        Select::make('main_warehouse_id')
                            ->label('Main warehouse')
                            ->required()
                            ->placeholder('select other main warehouse')
                            ->options(MainWarehouse::whereNot('id', $record->main_warehouse_id)->whereNot('name', MainWarehouse::DPWORLDWAREHOUSE)->get()->pluck('name', 'id')->toArray())
                    ])
                    ->successNotification(
                        Notification::make('success')
                            ->title('Device transfered')
                            ->body('device has been successfully transfered.'),
                    ),
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
                            ->options(MainWarehouse::whereNot('name', MainWarehouse::DPWORLDWAREHOUSE)->whereNot('name', MainWarehouse::HQWAREHOUSE)->get()->pluck('name', 'id')->toArray())
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
            'index' => Pages\ManageHQMainWarehouses::route('/'),
        ];
    }
}
