<?php

namespace App\Filament\Resources\WarehouseDeviceRequestResource\RelationManagers;

use App\Models\WarehouseDeviceRequestedDevice;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('transfer')
                    ->color('success')
                    ->action(fn() => null)
                    ->requiresConfirmation()
                    ->modalSubheading(fn ($record) => 'you are about to transfer a single device to ' .$record->warehouseDeviceRequest->campaign->district->district. ' District')
                    ->modalButton('transfer device')
                    ->icon('heroicon-o-paper-airplane')
                    ->label('Transfer')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('transfer selected')
                    ->icon('heroicon-o-paper-airplane')
            ]);
    }
}
