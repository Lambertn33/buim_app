<?php

namespace App\Filament\Resources\TechnicianResource\Pages;

use App\Filament\Resources\TechnicianResource;
use App\Models\Role;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListTechnicians extends ListRecords
{
    protected static string $resource = TechnicianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTableQuery(): Builder
    {
        $authenticatedUser = Auth::user();
        switch ($authenticatedUser->role->role) {
            case Role::ADMIN_ROLE:
                return parent::getTableQuery();
                break;

            case Role::DISTRICT_MANAGER_ROLE:
                $authenticatedManager = $authenticatedUser->manager;
                $district = $authenticatedManager->district;
                return parent::getTableQuery()->where('district_id', $district->id);
                break;

            case Role::SECTOR_LEADER_ROLE:
                $authenticatedLeader = $authenticatedUser->leader;
                $district = $authenticatedLeader->district;
                return parent::getTableQuery()->where('district_id', $district->id);
                break;

            default:
                break;
        }
    }
}
