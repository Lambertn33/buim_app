<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubStockRequestResource\Pages;
use App\Filament\Resources\SubStockRequestResource\RelationManagers;
use App\Models\Campaign;
use App\Models\Role;
use App\Models\StockModel;
use App\Models\SubStockRequest;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
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
                Card::make()
                    ->columns(2)
                    ->schema([
                        Select::make('campaign_id')
                            ->label('campaign')
                            ->placeholder('select one of your campaigns')
                            ->required()
                            ->options(
                                Campaign::where('manager_id', Auth::user()->manager->id)
                                    ->get()
                                    ->pluck('title', 'id')
                                    ->toArray()
                            ),
                        Select::make('model_id')
                            ->label('model')
                            ->placeholder('select device model')
                            ->required()
                            ->options(
                                StockModel::get()->pluck('name', 'id')->toArray()
                            ),
                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign.title')
                    ->searchable(),
                TextColumn::make('campaign.district')
                    ->searchable()
                    ->visible(Auth::user()->role->role === Role::ADMIN_ROLE),
                TextColumn::make('model.name')
                    ->label('device model'),
                TextColumn::make('quantity')
                    ->label('requested quantity'),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => static fn ($state): bool => $state === self::$model::REQUESTED,
                        'warning' => static fn ($state): bool => $state === self::$model::READY_FOR_LOADING,
                        'warning' => static fn ($state): bool => $state === self::$model::CONTRACT_PRINTING,
                        'success' => static fn ($state): bool => $state === self::$model::DELIVERED,
                        'primary' => static fn ($state): bool => $state === self::$model::VERIFIED,
                    ]),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->requiresConfirmation()
                    ->modalSubheading('update stock request status')
                    ->modalButton('update status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(SubStockRequest::STOCKREQUESTSTATUS)
                            ->required(),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn ($record) => $record->status !== SubStockRequest::REQUESTED),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSubStockRequests::route('/'),
        ];
    }
}
