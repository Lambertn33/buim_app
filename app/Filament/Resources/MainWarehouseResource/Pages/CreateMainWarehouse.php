<?php

namespace App\Filament\Resources\MainWarehouseResource\Pages;

use App\Filament\Resources\MainWarehouseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateMainWarehouse extends CreateRecord
{
    protected static string $resource = MainWarehouseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['id'] = Str::uuid()->toString();
        $data['location'] = 'KIGALI';
        return $data;
    }
}
