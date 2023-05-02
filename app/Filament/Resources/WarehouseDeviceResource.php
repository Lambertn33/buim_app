<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseDeviceResource\Pages;
use App\Filament\Resources\WarehouseDeviceResource\RelationManagers;
use App\Models\District;
use App\Models\Role;
use App\Models\StockModel;
use App\Models\WarehouseDevice;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class WarehouseDeviceResource extends Resource
{
    protected static ?string $model = WarehouseDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'district warehouses inventory';

    protected static ?string $navigationLabel = 'Warehouse devices';

    protected static ?int $navigationSort = 3;

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
                    -> searchable(),
                TextColumn::make('warehouse.district.district')
                    ->visible(Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE),
                TextColumn::make('warehouse.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('serial_number')
                    ->sortable()
                    ->searchable()
                    ->label('Serial number')
            ])
            ->filters([
                SelectFilter::make('district_id')
                    ->label('Filter by District')
                    ->searchable()
                    ->visible(Auth::user()->role->role !==Role::DISTRICT_MANAGER_ROLE)
                    ->options(District::orderBy('district', 'asc')->get()->pluck('district', 'id')->toArray()),
                SelectFilter::make('model_id')
                    ->label('Filter by device models')
                    ->searchable()
                    ->options(StockModel::get()->pluck('name', 'id')->toArray())

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
