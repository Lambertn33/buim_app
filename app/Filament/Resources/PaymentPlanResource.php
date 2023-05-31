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
                    ->label('Payment plan')
                    ->unique(ignoreRecord: true)
                    ->maxLength(20)
                    ->required(),
                TextInput::make('percentage')
                    ->label('Customer contribution (%)')
                    ->numeric()
                    ->maxValue(100)
                    ->required(),
                TextInput::make('downpayment')
                    ->label('Downpayment (%)')
                    ->numeric()
                    ->maxValue(100)
                    ->required(),
                TextInput::make('duration')
                    ->label('Duration (In days)')
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
                    ->label('Payment category')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('percentage')
                    ->label('Customer contribution (%)')
                    ->suffix('%')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('downpayment')
                    ->label('Downpayment (%)')
                    ->suffix('%')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('duration')
                    ->label('Duration')
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
