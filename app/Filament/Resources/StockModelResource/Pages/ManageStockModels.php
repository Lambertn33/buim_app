<?php

namespace App\Filament\Resources\StockModelResource\Pages;

use App\Filament\Resources\StockModelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class ManageStockModels extends ManageRecords
{
    protected static string $resource = StockModelResource::class;

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
                    ->title('Device model registered')
                    ->body('The model has been successfully created.'),
            ),
        ];
    }
}
