<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseDeviceRequestResource\Pages;
use App\Filament\Resources\WarehouseDeviceRequestResource\RelationManagers;
use App\Models\WarehouseDeviceRequest;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WarehouseDeviceRequestResource extends Resource
{
    protected static ?string $model = WarehouseDeviceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'district requested stock';

    protected static ?string $navigationLabel = 'Requested devices';

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
                    ->searchable()
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
            //
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
