<?php

namespace App\Filament\Resources\ScreeningResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeviceRelationManager extends RelationManager
{
    protected static string $relationship = 'device';

    protected static ?string $recordTitleAttribute = 'device_name';

    protected static ?string $pluralModelLabel = 'Device Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('device_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_name'),
                Tables\Columns\TextColumn::make('model.name')
                    ->label('device model'),
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('device serial number'),
                Tables\Columns\TextColumn::make('device_price')
                    ->suffix(' FRWS'),
                Tables\Columns\TextColumn::make('partner_contribution_in_percentage')
                    ->formatStateUsing(fn ($livewire) => $livewire->ownerRecord->getPartnerContributionPercentage())
                    ->label('Partner contribution (%)')
                    ->suffix(' %'),
                Tables\Columns\TextColumn::make('partner_contribution')
                    ->label('Partner contribution')
                    ->formatStateUsing(fn ($livewire) => $livewire->ownerRecord->getPartnerContribution())
                    ->suffix(' FRWS'),
                Tables\Columns\TextColumn::make('customer_contribution')
                    ->formatStateUsing(fn ($livewire) => $livewire->ownerRecord->getCustomerContributionPercentage())
                    ->label('Customer contribution (%)')
                    ->suffix(' %'),
                Tables\Columns\TextColumn::make('screener.total_amount_to_pay')
                    ->label('Customer ccntribution (FRWS)')
                    ->suffix(' FRWS'),
                Tables\Columns\TextColumn::make('customer_total_paid')
                    ->formatStateUsing(fn ($livewire) => $livewire->ownerRecord->total_amount_paid)
                    ->label('Total customer payment (FRWS)')
                    ->suffix(' FRWS'),
                Tables\Columns\TextColumn::make('customer_remaining_to_pay')
                    ->formatStateUsing(fn ($livewire) => $livewire->ownerRecord->getCustomerRemainingAmountToPay())
                    ->label('Remaining customer payment (FRWS)')
                    ->suffix(' FRWS'),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
