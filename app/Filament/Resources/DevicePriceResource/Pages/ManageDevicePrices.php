<?php

namespace App\Filament\Resources\DevicePriceResource\Pages;

use App\Filament\Resources\DevicePriceResource;
use App\Services\StockServices;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManageDevicePrices extends ManageRecords
{
    protected static string $resource = DevicePriceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add Device price')
            ->mutateFormDataUsing(function (array $data): array {
                $data['id'] = Str::uuid()->toString();
                (new StockServices)->setDevicePrice($data['device_name'], $data['device_price']);
                return $data;
            }),
        ];
    }
}
