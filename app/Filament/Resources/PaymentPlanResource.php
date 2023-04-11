<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentPlanResource\Pages;
use App\Filament\Resources\PaymentPlanResource\RelationManagers;
use App\Models\PaymentPlan;
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

class PaymentPlanResource extends Resource
{
    protected static ?string $model = PaymentPlan::class;

    protected static ?string $navigationGroup = 'Access control';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfPaymentPlans();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('payment title')
                    ->unique(ignoreRecord: true)
                    ->maxLength(20)
                    ->required(),
                TextInput::make('amount')
                    ->label('amount to pay')
                    ->numeric()
                    ->minValue(100)
                    ->required(),
                TextInput::make('duration')
                    ->label('duration (In days)')
                    ->numeric()
                    ->required()
                    ->minValue(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('payment title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('amount')
                    ->suffix(' FRWS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('duration')
                    ->label('duration')
                    ->sortable()
                    ->suffix(' days')
                    ->searchable()
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
            'index' => Pages\ManagePaymentPlans::route('/'),
        ];
    }
}
