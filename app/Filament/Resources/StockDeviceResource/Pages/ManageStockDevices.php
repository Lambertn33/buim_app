<?php

namespace App\Filament\Resources\StockDeviceResource\Pages;

use App\Filament\Resources\StockDeviceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;

class ManageStockDevices extends ManageRecords
{
    protected static string $resource = StockDeviceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create device')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['id'] = Str::uuid()->toString();
             
                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Device registered')
                        ->body('New device has been successfully created.'),
                ),
                Action::make('import stock'),
        ];
    }
}
