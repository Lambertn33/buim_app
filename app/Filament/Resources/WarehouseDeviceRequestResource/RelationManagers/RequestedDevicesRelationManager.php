<?php

namespace App\Filament\Resources\WarehouseDeviceRequestResource\RelationManagers;

use App\Models\Role;
use App\Models\WarehouseDeviceRequestedDevice;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

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
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('Approve and Transfer devices')
                    ->color('success')
                    ->action(fn () => null)
                    ->requiresConfirmation()
                    ->modalSubheading('you are about to transfer all devices')
                    ->modalButton('transfer device')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(Auth::user()->role->role == Role::ADMIN_ROLE || Auth::user()->role->role == Role::STOCK_MANAGER_ROLE)
            ])
            ->actions([
                Tables\Actions\Action::make('transfer')
                    ->visible(Auth::user()->role->role == Role::ADMIN_ROLE || Auth::user()->role->role == Role::STOCK_MANAGER_ROLE)
                    ->color('success')
                    ->action(fn () => null)
                    ->requiresConfirmation()
                    ->modalSubheading(fn ($record) => 'you are about to transfer a single device to ' . $record->warehouseDeviceRequest->campaign->district->district . ' District')
                    ->modalButton('transfer device')
                    ->icon('heroicon-o-paper-airplane')
                    ->label('Transfer')
                    ->form([
                        Select::make('warehouse_id')
                            ->options(
                                fn ($record) =>
                                $record->warehouseDeviceRequest->campaign->district->warehouses()->get()->pluck('name', 'id')->toArray()
                            )
                    ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(Auth::user()->role->role == Role::ADMIN_ROLE || Auth::user()->role->role == Role::STOCK_MANAGER_ROLE),
                Tables\Actions\BulkAction::make('transfer selected')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(Auth::user()->role->role == Role::ADMIN_ROLE || Auth::user()->role->role == Role::STOCK_MANAGER_ROLE)
            ]);
    }
}
