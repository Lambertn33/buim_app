<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseDeviceTransferResource\Pages;
use App\Filament\Resources\WarehouseDeviceTransferResource\RelationManagers;
use App\Models\WarehouseDeviceTransfer;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WarehouseDeviceTransferResource extends Resource
{
    protected static ?string $model = WarehouseDeviceTransfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Inventory - District warehouses';

    protected static ?string $navigationLabel = 'Warehouse device transfers';

    protected static ?string $pluralModelLabel = 'District Warehouse Device Transfers';

    protected static ?int $navigationSort = 4;

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfWarehouseDeviceTransfers();
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
                    ->description(fn (WarehouseDeviceTransfer $record): string => $record->description),
                TextColumn::make('serial_number')
                    ->sortable()
                    ->searchable()
                    ->label('Device serial number'),
                TextColumn::make('warehouse_sender_id')
                    ->label('Sender')
                    ->formatStateUsing(
                        fn (WarehouseDeviceTransfer $record): string =>
                        $record->sentBy()
                    ),
                TextColumn::make('warehouse_receiver_id')
                    ->label('Receiver')
                    ->formatStateUsing(fn (WarehouseDeviceTransfer $record): string =>
                    $record->receivedBy()),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => WarehouseDeviceTransfer::PENDING,
                        'success' => WarehouseDeviceTransfer::APPROVED,
                        'danger' => WarehouseDeviceTransfer::REJECTED,
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWarehouseDeviceTransfers::route('/'),
        ];
    }
}
