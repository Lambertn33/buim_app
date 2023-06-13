<?php

namespace App\Filament\Resources\ScreeningResource\RelationManagers;

use App\Jobs\TokenGenerated;
use App\Models\Screening;
use App\Models\ScreeningPayment;
use App\Models\ScreeningToken;
use App\Services\ScreeningServices;
use App\Services\TokensServices;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'amount';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('amount')
                    ->required()
                    ->maxLength(255),
                Select::make('payment_mode')
                    ->required()
                    ->searchable()
                    ->placeholder('Select payment method')
                    ->options([
                        'MANUAL' => 'MANUAL',
                        'MTN' => 'MTN',
                        'AIRTEL' => 'AIRTEL'
                    ]),
                TextInput::make('payment_type')
                    ->disabled()
                    ->default(ScreeningPayment::DOWNPAYMENT)
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
                    ->formatStateUsing(fn (ScreeningPayment $record) => $record->paymentToken?->token)
                    ->searchable(),
                TextColumn::make('token_validity')
                    ->sortable()
                    ->formatStateUsing(fn (ScreeningPayment $record) => $record->paymentToken ?  $record->paymentToken->validity_days. ' days' : '')
                    ->searchable(),
                TextColumn::make('remaining_days')
                    ->label('Remaining days to pay')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (RelationManager $livewire) => '' . $livewire->ownerRecord->remaining_days_to_pay . ' days')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New payment record')
                    ->action(fn (RelationManager $livewire, array $data) => (new ScreeningServices)->addNewScreeningPayment($livewire->ownerRecord, $data))
                    ->visible(fn (RelationManager $livewire) => $livewire->ownerRecord->confirmation_status === Screening::ACTIVE_CUSTOMER),
            ])
            ->actions([
                Action::make('Generate token')
                    ->color('success')
                    ->visible(fn ($record) => !$record->paymentToken()->exists())
                    ->action(function ($record) {

                        $paymentType = $record->payment_type;
                        $devicePrice = $record->screener->device->device_price;
                        $key = '';
                        $characters = '0123456789ABCDEF';
                        $duration = $paymentType === ScreeningPayment::ADVANCED_PAYMENT ? 30 : $record->screener->paymentPlan->duration;
                        $dailyPayment = (int) ceil($devicePrice / $duration);
                        $numberOfPaidDays = $paymentType === ScreeningPayment::ADVANCED_PAYMENT ? 30 : (int) round($record->amount / $dailyPayment);

                        for ($i = 0; $i < 20; $i++) {
                            $index = rand(0, strlen($characters) - 1);
                            $key .= $characters[$index];
                        }
                        $data = [
                            'command' => 1,
                            'data' => $numberOfPaidDays,
                            'count' => (new ScreeningServices)->getLastGeneratedTokenCount(),
                            'key' => $key
                        ];
                        try {
                            $tokenResponse = (new TokensServices)->generateToken($data);
                            // API returned success
                            $token = $tokenResponse['token'];
                            $newScreeningToken = [
                                'id' => Str::uuid()->toString(),
                                'screening_payment_id' => $record->id,
                                'token' => $token,
                                'validity_days' => $numberOfPaidDays,
                                'key' => $key,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                            ScreeningToken::insert($newScreeningToken);
                            // after token generated send SMS to user
                            TokenGenerated::dispatch($record->screener, $token, $numberOfPaidDays);
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Error')
                                ->body('an error occured on generating the token.. please try again')
                                ->danger()
                                ->send();
                            return;
                        }
                    })->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Token generated')
                            ->body('The token has been successfully generated.'),
                    ),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
