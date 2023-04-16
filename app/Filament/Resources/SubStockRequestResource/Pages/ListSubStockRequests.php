<?php

namespace App\Filament\Resources\SubStockRequestResource\Pages;

use App\Filament\Resources\SubStockRequestResource;
use App\Models\SubStockRequest;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSubStockRequests extends ListRecords
{
    protected static string $resource = SubStockRequestResource::class;

    protected function getActions(): array
    {
        return [
            //
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->whereNot('request_status', SubStockRequest::INITIATED);
    }
}
