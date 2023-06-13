<?php

namespace App\Filament\Resources\ScreeningResource\RelationManagers;

use App\Jobs\TokenGenerated;
use App\Models\Screening;
use App\Models\ScreeningPayment;
use App\Models\ScreeningToken;
use App\Services\ScreeningServices;
use App\Services\TokensServices;
use Filament\Forms;
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
                    ->formatStateUsing(fn (ScreeningPayment $record) => $record->paymentToken?->token)
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

                        for ($i = 0; $i < 20; $i++) {
                            $index = rand(0, strlen($characters) - 1);
                            $key .= $characters[$index];
                        }
                        if ($record->payment_type === ScreeningPayment::ADVANCED_PAYMENT) {
                            // on advanced payment, the first token has 30 days
                            $duration = 30;
                            $data = [
                                'command' => 1,
                                'data' => $duration,
                                'count' => (new ScreeningServices)->getLastGeneratedTokenCount(),
                                'key' => $key
                            ];
                            try {
                                $tokenResponse = (new TokensServices)->generateToken($data);
                                if (!is_null($tokenResponse) && array_key_exists("token", $tokenResponse)) {
                                    // API returned success
                                    $token = $tokenResponse['token'];
                                    $newScreeningToken = [
                                        'id' => Str::uuid()->toString(),
                                        'screening_payment_id' => $record->id,
                                        'token' => $token,
                                        'validity_days' => $duration,
                                        'key' => $key,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ];
                                    ScreeningToken::insert($newScreeningToken);
                                    TokenGenerated::dispatch($record->screener, $token, $duration);
                                    // after token generated send SMS to user
                                } else {
                                    dd('there is an error');
                                }
                            } catch (\Throwable $th) {
                                //throw $th;
                            }
                        } else {
                            dd ('hey');
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
