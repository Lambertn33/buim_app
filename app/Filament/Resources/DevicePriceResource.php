<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DevicePriceResource\Pages;
use App\Filament\Resources\DevicePriceResource\RelationManagers;
use App\Models\DevicePrice;
use App\Models\MainWarehouse;
use App\Models\MainWarehouseDevice;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DevicePriceResource extends Resource
{
    protected static ?string $model = DevicePrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'inventory settings';

    protected static ?string $navigationLabel = 'Price settings';

    protected static ?string $pluralModelLabel = 'Price settings';

    protected static ?int $navigationSort = 2;

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfDevicePrices();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        Select::make('device_name')
                            ->required()
                            ->label('select device name')
                            ->unique(ignoreRecord: true)
                            ->options(MainWarehouseDevice::whereHas('mainWarehouse', function ($query) {
                                $query->where('name', MainWarehouse::DPWORLDWAREHOUSE);
                            })->distinct()->pluck('device_name', 'device_name')->toArray()),
                        TextInput::make('device_price')
                            ->required()
                            ->numeric()
                            ->minValue(1000)
                            ->label('Enter device price')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device_name')
                    ->sortable()
                    ->searchable()
                    ->label('Device name'),
                TextColumn::make('device_price')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => ("{$state} FRW"))
                    ->label('Device price'),
            ])
            ->filters([
                //
            ])
            ->bulkActions([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDevicePrices::route('/'),
        ];
    }
}
