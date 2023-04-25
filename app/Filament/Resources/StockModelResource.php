<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockModelResource\Pages;
use App\Filament\Resources\StockModelResource\RelationManagers;
use App\Models\Role;
use App\Models\StockModel;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class StockModelResource extends Resource
{
    protected static ?string $model = StockModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'inventory settings';

    protected static ?string $navigationLabel = 'Main Device models';

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfDeviceModels();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->label('model name')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('number of devices')
                    ->visible(Auth::user()->role->role === Role::ADMIN_ROLE)
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
            'index' => Pages\ManageStockModels::route('/'),
        ];
    }    
}
