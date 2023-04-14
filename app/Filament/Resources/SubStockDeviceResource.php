<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubStockDeviceResource\Pages;
use App\Filament\Resources\SubStockDeviceResource\RelationManagers;
use App\Models\SubStockDevice;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubStockDeviceResource extends Resource
{
    protected static ?string $model = SubStockDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'overall stock';

    protected static ?string $navigationLabel = 'District devices';

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
                //
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
            'index' => Pages\ManageSubStockDevices::route('/'),
        ];
    }    
}