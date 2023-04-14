<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendingStockDeviceResource\Pages;
use App\Filament\Resources\PendingStockDeviceResource\RelationManagers;
use App\Models\PendingStockDevice;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PendingStockDeviceResource extends Resource
{
    protected static ?string $model = PendingStockDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static ?string $navigationLabel = 'Pending Stock Devices';

    protected static ?string $navigationGroup = 'overall stock';

    protected static ?string $pluralModelLabel = 'Available Pending Stock Devices';

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermission('stock_pending_access');
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
                    ->formatStateUsing(fn (string $state): string => 
                        User::whereHas('manufacturer', function($query) use($state){
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
            'index' => Pages\ManagePendingStockDevices::route('/'),
        ];
    }
}
