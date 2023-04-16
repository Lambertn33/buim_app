<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubStockRequestResource\Pages;
use App\Filament\Resources\SubStockRequestResource\RelationManagers;
use App\Models\SubStockRequest;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubStockRequestResource extends Resource
{
    protected static ?string $model = SubStockRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'overall stock';

    protected static ?string $navigationLabel = 'Stock Requests';

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
            //
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
