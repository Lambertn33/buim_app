<?php

namespace App\Filament\Resources\SubStockRequestResource\Pages;

use App\Filament\Resources\SubStockRequestResource;
use App\Models\Role;
use App\Models\SubStockRequest;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        return Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE ?
        parent::getTableQuery()->whereNot('request_status', SubStockRequest::INITIATED)
        ->where('manager_id', Auth::user()->manager->id)
        : parent::getTableQuery()->whereNot('request_status', SubStockRequest::INITIATED);
    }
}
