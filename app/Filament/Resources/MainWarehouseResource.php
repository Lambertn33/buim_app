<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainWarehouseResource\Pages;
use App\Filament\Resources\MainWarehouseResource\RelationManagers;
use App\Models\MainWarehouse;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

    protected static ?string $navigationGroup = 'Inventory - Main warehouses';

    protected static ?string $navigationLabel = 'Overview';

    protected static ?int $navigationSort = 1;
    
    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfMainWarehouses();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Main warehouse name')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Main warehouse description')
                            ->required()
                    ])
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
                TextColumn::make('location'),
                TextColumn::make('devices_count')
                    ->label('Available quantity')
                    ->sortable()
                    ->counts('devices')
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
