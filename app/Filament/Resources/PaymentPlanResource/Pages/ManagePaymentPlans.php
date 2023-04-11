<?php

namespace App\Filament\Resources\PaymentPlanResource\Pages;

use App\Filament\Resources\PaymentPlanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class ManagePaymentPlans extends ManageRecords
{
    protected static string $resource = PaymentPlanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['id'] = Str::uuid()->toString();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Payment plan registered')
                        ->body('The payment plan has been successfully created.'),
                ),
            Actions\EditAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Payment plan updated')
                        ->body('The payment plan has been successfully updated.'),
                ),
        ];
    }
}
