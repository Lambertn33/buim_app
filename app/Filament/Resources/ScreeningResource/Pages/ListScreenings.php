<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class ListScreenings extends ListRecords
{
    protected static string $resource = ScreeningResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return parent::getTableQuery();
        } elseif (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return parent::getTableQuery()->where('manager_id', Auth::user()->manager->id);
        } elseif(Auth::user()->role->role === Role::SECTOR_LEADER_ROLE) {
            return parent::getTableQuery()->where('leader_id', Auth::user()->leader->id);
        }
    }
}
