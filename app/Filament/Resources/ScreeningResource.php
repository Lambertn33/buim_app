<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScreeningResource\Pages;
use App\Filament\Resources\ScreeningResource\RelationManagers;
use App\Models\Campaign;
use App\Models\MainWarehouse;
use App\Models\MainWarehouseDevice;
use App\Models\PaymentPlan;
use App\Models\Screening;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScreeningResource extends Resource
{
    protected static ?string $model = Screening::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'activities';

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfScreenings();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        Select::make('campaign_id')
                            ->label('prospect campaign')
                            ->placeholder('select campaign')
                            ->required()
                            ->reactive()
                            ->options(Campaign::where('status', Campaign::ONGOING)->get()->pluck('title', 'id')->toArray()),
                        Select::make('payment_id')
                            ->label('prospect payment plan')
                            ->placeholder('select payment plan')
                            ->required()
                            ->options(PaymentPlan::get()->pluck('title', 'id')->toArray()),
                        TextInput::make('prospect_names')
                            ->label('prospect names')
                            ->required(),
                        TextInput::make('prospect_telephone')
                            ->label('prospect telephone')
                            ->tel()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('prospect_national_id')
                            ->label('prospect national ID')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->numeric()
                            ->minLength(16)
                            ->maxLength(16),
                        TextInput::make('sector')
                            ->label('prospect sector')
                            ->required(),
                        TextInput::make('cell')
                            ->label('prospect cell')
                            ->required(),
                        TextInput::make('village')
                            ->label('prospect village')
                            ->required(),
                        Select::make('eligibility_status')
                            ->required()
                            ->placeholder('select eligibility')
                            ->label('Eligibility status')
                            ->searchable()
                            ->options([
                                'ELIGIBLE' => 'ELIGIBLE',
                                'NOT ELIGIBLE' => 'NOT ELIGIBLE',                            
                            ]),
                        Select::make('proposed_device_name')
                            ->label('proposed device')
                            ->searchable()
                            ->required()
                            ->placeholder('select device')
                            ->options(MainWarehouseDevice::whereHas('mainWarehouse', function($query){
                                $query->where('name', MainWarehouse::DPWORLDWAREHOUSE)
                                    ->where('is_approved', true);
                            })->get()->pluck('device_name', 'device_name')->toArray())

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('prospect_names')
                    ->label('Names')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('prospect_telephone')
                    ->label('telephone')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('prospect_code')
                    ->label('code')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('eligibility_status')
                    ->colors([
                        'warning' => static fn ($state): bool => $state === self::$model::NOT_ELIGIBLE,
                        'success' => static fn ($state): bool => $state === self::$model::ELIGIBLE,
                    ]),
                BadgeColumn::make('confirmation_status')
                    ->colors([
                        'danger' => static fn ($state): bool => $state === self::$model::PROSPECT,
                        'warning' => static fn ($state): bool => $state === self::$model::PRE_REGISTERED,
                        'success' => static fn ($state): bool => $state === self::$model::ACTIVE_CUSTOMER,
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListScreenings::route('/'),
            'create' => Pages\CreateScreening::route('/create'),
            'view' => Pages\ViewScreening::route('/{record}'),
            'edit' => Pages\EditScreening::route('/{record}/edit'),
        ];
    }
}
