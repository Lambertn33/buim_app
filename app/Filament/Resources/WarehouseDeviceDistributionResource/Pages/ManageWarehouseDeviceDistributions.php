<?php

namespace App\Filament\Resources\WarehouseDeviceDistributionResource\Pages;

use App\Filament\Resources\WarehouseDeviceDistributionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManageWarehouseDeviceDistributions extends ManageRecords
{
    protected static string $resource = WarehouseDeviceDistributionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data){
                    $data['id'] = Str::uuid()->toString();
                    dd ($data);
                })
        ];
    }
}
