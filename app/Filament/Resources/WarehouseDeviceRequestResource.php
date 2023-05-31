<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseDeviceRequestResource\Pages;
use App\Filament\Resources\WarehouseDeviceRequestResource\RelationManagers;
use App\Filament\Resources\WarehouseDeviceRequestResource\RelationManagers\RequestedDevicesRelationManager;
use App\Models\Role;
use App\Models\WarehouseDeviceRequest;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class WarehouseDeviceRequestResource extends Resource
{
    protected static ?string $model = WarehouseDeviceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Inventory - District warehouses';

    protected static ?string $navigationLabel = 'Campaign request';

    protected static ?string $pluralModelLabel = 'Request list';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('request_id')
                            ->formatStateUsing(fn (string $state): string => sprintf("%08d", $state)),
                        TextInput::make('request_status'),
                        TextInput::make('confirmation_status')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign.district.district'),
                TextColumn::make('campaign.title'),
                TextColumn::make('request_id')
                    ->label('Request ID')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => sprintf("%08d", $state))
                    ->searchable(),
                TextColumn::make('Devices')
                    ->label('number of requested devices')
                    ->sortable()
                    ->formatStateUsing(fn (WarehouseDeviceRequest $record): string => $record->getTotalNumberOfRequestedDevices())
                    ->searchable(),
                SelectColumn::make('request_status')
                    ->options([
                        'REQUESTED' => WarehouseDeviceRequest::REQUESTED,
                        'VERIFIED' => WarehouseDeviceRequest::VERIFIED,
                        'CONTRACT_PRINTING' => WarehouseDeviceRequest::CONTRACT_PRINTING,
                        'READY_FOR_LOADING' => WarehouseDeviceRequest::READY_FOR_LOADING,
                        'DELIVERED' => WarehouseDeviceRequest::DELIVERED,
                    ])->disabled(function (WarehouseDeviceRequest $record) {
                        return Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE
                            ? true
                            : ($record->request_status === WarehouseDeviceRequest::DELIVERED ? true : false);
                    }),
                SelectColumn::make('confirmation_status')
                    ->options([
                        'PENDING' => WarehouseDeviceRequest::PENDING,
                        'RECEIVED' => WarehouseDeviceRequest::RECEIVED,
                    ])->disabled(function (WarehouseDeviceRequest $record) {
                        return Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE
                            ? true
                            : ($record->confirmation_status === WarehouseDeviceRequest::RECEIVED ? true :

                            ($record->request_status !== WarehouseDeviceRequest::DELIVERED ? true : false));
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RequestedDevicesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWarehouseDeviceRequests::route('/'),
            'create' => Pages\CreateWarehouseDeviceRequest::route('/create'),
            'view' => Pages\ViewWarehouseDeviceRequest::route('/{record}'),
            'edit' => Pages\EditWarehouseDeviceRequest::route('/{record}/edit'),
        ];
    }
}
