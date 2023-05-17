<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicianResource\Pages;
use App\Filament\Resources\TechnicianResource\RelationManagers;
use App\Models\District;
use App\Models\Role;
use App\Models\Technician;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TechnicianResource extends Resource
{
    protected static ?string $model = Technician::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Inventory - District warehouses';

    protected static ?string $navigationLabel = 'District Technicians';

    protected static ?string $pluralModelLabel = 'District Technicians';

    protected static ?int $navigationSort = 5;

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfTechnicians();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('names')
                    ->required()
                    ->label('Technician names'),
                TextInput::make('telephone')
                    ->label('Technician telephone')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true),              
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('names')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telephone')
                    ->searchable(),
                SelectColumn::make('status')
                    ->options([
                        'ACTIVE' => self::$model::ACTIVE,
                        'INACTIVE' => self::$model::INACTIVE
                    ])->disabled(Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE),
                TextColumn::make('district.district')
                    ->visible(Auth::user()->role->role == Role::ADMIN_ROLE)
            ])
            ->filters([
                SelectFilter::make('district_id')
                    ->label('Filter by District')
                    ->visible(Auth::user()->role->role == Role::ADMIN_ROLE)
                    ->options(District::get()->pluck('district', 'id')->toArray()),
                SelectFilter::make('status')
                    ->label('Filter by status')
                    ->visible(Auth::user()->role->role == Role::ADMIN_ROLE)
                    ->options([
                        'ACTIVE' => self::$model::ACTIVE,
                        'INACTIVE' => self::$model::INACTIVE
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTechnicians::route('/'),
            'create' => Pages\CreateTechnician::route('/create'),
            'edit' => Pages\EditTechnician::route('/{record}/edit'),
        ];
    }
}
