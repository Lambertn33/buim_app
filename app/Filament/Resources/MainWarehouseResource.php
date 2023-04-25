<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainWarehouseResource\Pages;
use App\Filament\Resources\MainWarehouseResource\RelationManagers;
use App\Models\MainWarehouse;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MainWarehouseResource extends Resource
{
    protected static ?string $model = MainWarehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'main warehouses inventory';

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
                TextColumn::make('name')
                    ->label('Warehouse')
                    ->searchable()
                    ->description(fn (MainWarehouse $record): string => $record->description)
                    ->sortable(),
                TextColumn::make('location')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMainWarehouses::route('/'),
            'create' => Pages\CreateMainWarehouse::route('/create'),
            'view' => Pages\ViewMainWarehouse::route('/{record}'),
            'edit' => Pages\EditMainWarehouse::route('/{record}/edit'),
        ];
    }    
}
