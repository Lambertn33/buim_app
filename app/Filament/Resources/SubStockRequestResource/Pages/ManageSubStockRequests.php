<?php

namespace App\Filament\Resources\SubStockRequestResource\Pages;

use App\Filament\Resources\SubStockRequestResource;
use App\Models\SubStockRequest;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class ManageSubStockRequests extends ManageRecords
{
    protected static string $resource = SubStockRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Request stock')
                ->label('Request stock')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['id'] = Str::uuid()->toString();
                    $data['status'] = SubStockRequest::REQUESTED;
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
