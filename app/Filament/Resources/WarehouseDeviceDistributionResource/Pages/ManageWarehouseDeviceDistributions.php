<?php

namespace App\Filament\Resources\WarehouseDeviceDistributionResource\Pages;

use App\Filament\Resources\WarehouseDeviceDistributionResource;
use App\Services\ScreeningServices;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWarehouseDeviceDistributions extends ManageRecords
{
    protected static string $resource = WarehouseDeviceDistributionResource::class;

    protected static bool $canCreateAnother = false;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New distribution')
                ->action(function (array $data){
                    (new ScreeningServices)->createScreeningDistribution($data);
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Distribution completed')
                        ->body('The distribution has been successfully completed.'),
                ),
        ];
    }
}
