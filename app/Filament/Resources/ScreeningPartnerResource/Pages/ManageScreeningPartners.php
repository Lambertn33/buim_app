<?php

namespace App\Filament\Resources\ScreeningPartnerResource\Pages;

use App\Filament\Resources\ScreeningPartnerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class ManageScreeningPartners extends ManageRecords
{
    protected static string $resource = ScreeningPartnerResource::class;

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
                    ->title('Partner registered')
                    ->body('The partner has been successfully created.'),
            ),
        ];
    }
}
