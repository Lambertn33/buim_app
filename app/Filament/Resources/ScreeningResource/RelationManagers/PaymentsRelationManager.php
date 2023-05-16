<?php

namespace App\Filament\Resources\ScreeningResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'amount_paid';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount_paid')
                    ->label('paid amount')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('amount paid')
                    ->formatStateUsing(fn ($record): string => $record->amount_paid . ' FRWS'),
                Tables\Columns\TextColumn::make('remaining_amount')
                    ->label('remaining amount')
                    ->formatStateUsing(fn ($record): string => $record->remaining_amount . ' FRWS'),
                Tables\Columns\TextColumn::make('remaining_months_to_pay')
                    ->label('pending months')
                    ->formatStateUsing(fn ($record): string => $record->remaining_months_to_pay . ' months'),
                Tables\Columns\TextColumn::make('next_payment_date')
                    ->label('next payment date')

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->action(fn() => dd('to be done'))
                    ->visible(function (RelationManager $livewire) {
                        return $livewire->ownerRecord->payments->count() > 0 ? true : false;
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
