<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubStockRequestResource\Pages;
use App\Filament\Resources\SubStockRequestResource\RelationManagers;
use App\Filament\Resources\SubStockRequestResource\RelationManagers\RequestedDevicesRelationManager;
use App\Models\SubStockRequest;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubStockRequestResource extends Resource
{
    protected static ?string $model = SubStockRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'overall stock';

    protected static ?string $navigationLabel = 'Stock Requests';

    protected static ?string $pluralModelLabel = 'Requested Stock';

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfRequestedDevices();
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
                TextColumn::make('request_id')
                    ->label('Request ID')
                    ->formatStateUsing(fn (string $state): string => sprintf("%08d", $state))
                    ->searchable(),
                TextColumn::make('Devices')
                    ->label('number of requested devices')
                    ->formatStateUsing(fn (SubStockRequest $record): string => $record->getTotalNumberOfRequestedDevices())
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSubStockRequests::route('/'),
            'view' => Pages\ViewSubStockRequest::route('/{record}'),
            'edit' => Pages\EditSubStockRequest::route('/{record}/edit'),
        ];
    }    
}
