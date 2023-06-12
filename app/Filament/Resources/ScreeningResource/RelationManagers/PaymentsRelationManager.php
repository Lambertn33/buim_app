<?php

namespace App\Filament\Resources\ScreeningResource\RelationManagers;

use App\Models\Screening;
use App\Models\ScreeningPayment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'amount';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->label('Paid amount')
                    ->sortable()
                    ->searchable()
                    ->suffix('FRWS'),
                TextColumn::make('payment_type')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => $state === ScreeningPayment::ADVANCED_PAYMENT ? 'ADVANCED PAYMENT' : 'DOWNPAYMENT'),
                TextColumn::make('payment_mode')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => $state === ScreeningPayment::MANUAL_PAYMENT ? 'MANUAL PAYMENT / CASH' : ($state === ScreeningPayment::MOMO_PAYMENT ? 'Mobile money' : 'Airtel money'
                    )),
                TextColumn::make('payment_date')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (ScreeningPayment $record) => $record->created_at->format('Y-m-d')),
                TextColumn::make('token')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('remaining_days')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => '' . $state . ' days')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New payment record'),
                Action::make('New token generation')
                    ->color('success')
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
