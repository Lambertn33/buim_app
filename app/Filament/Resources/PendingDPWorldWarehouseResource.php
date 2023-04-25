<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendingDPWorldWarehouseResource\Pages;
use App\Filament\Resources\PendingDPWorldWarehouseResource\RelationManagers;
use App\Models\PendingDPWorldWarehouse;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use App\Models\MainWarehouseDevice;
use App\Models\Role;
use App\Models\User;
use App\Services\NavigationBadgesServices;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class PendingDPWorldWarehouseResource extends Resource
{
    protected static ?string $model = MainWarehouseDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationLabel = 'Pending Devices';

    protected static ?string $navigationGroup = 'stock initialization';

    protected static ?string $slug = 'dp-world-pending-warehouse';

    protected static ?string $modelLabel = 'Pending Warehouse Devices';

    protected static ?int $navigationSort = 5;

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfPendingDPWorldWarehouseDevices();
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role->role === Role::ADMIN_ROLE ||
              Auth::user()->role->role === Role::MANUFACTURER_ROLE ||
            Auth::user()->role->role === Role::STOCK_MANAGER_ROLE;
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
            TextColumn::make('initialization_code')
                ->sortable()
                ->searchable()
                ->label('Initialization Code'),
            TextColumn::make('initialized_by')
                ->formatStateUsing(
                    fn (string $state): string =>
                    User::whereHas('manufacturer', function ($query) use ($state) {
                        $query->where('id', $state);
                    })->value('name')
                )
                ->hidden(Auth::user()->role->role === Role::MANUFACTURER_ROLE)
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePendingDPWorldWarehouses::route('/'),
        ];
    }    
}
