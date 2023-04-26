<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseResource\Pages;
use App\Filament\Resources\WarehouseResource\RelationManagers;
use App\Models\District;
use App\Models\Role;
use App\Models\Warehouse;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
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

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Warehouse name')
                            ->unique(ignoreRecord: true),
                        Select::make('district_id')
                            ->required()
                            ->label('Select district')
                            ->searchable()
                            ->options(District::orderBy('district', 'asc')->get()->pluck('district', 'id')->toArray())
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('district.district')
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('status')
                    ->options([
                        'CLOSED' => 'CLOSED',
                        'ACTIVE' => 'ACTIVE'
                    ])
                    ->sortable()
                    ->disablePlaceholderSelection()
                    ->disabled(Auth::user()->role->role !== Role::ADMIN_ROLE),
                TextColumn::make('manager.user.name')
                    ->label('Manager')
                    ->searchable()
                    ->visible(Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWarehouses::route('/'),
        ];
    }
}
