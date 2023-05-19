<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use App\Filament\Resources\ScreeningResource\Widgets\ScreeningsOverviewWidget;
use App\Models\Campaign;
use App\Models\PaymentPlan;
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
            Actions\CreateAction::make()
                ->visible(Auth::user()->role->role == Role::SECTOR_LEADER_ROLE)
                ->disabled(Campaign::where('status', Campaign::ONGOING)->whereHas('district', function($query) {
                    if (Auth::user()->role->role == Role::SECTOR_LEADER_ROLE) {
                        $query->where('id', Auth::user()->leader->district_id);
                    } else if(Auth::user()->role->role == Role::DISTRICT_MANAGER_ROLE) {
                        $query->where('id', Auth::user()->manager->district->id);
                    }
                })->count() < 1),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ScreeningsOverviewWidget::class
        ];
    }

    protected function getTableQuery(): Builder
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return parent::getTableQuery();
        } elseif (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return parent::getTableQuery()->whereHas('campaign', function($query){
                $query->where('district_id', Auth::user()->manager->district->id);
            });
        } elseif(Auth::user()->role->role === Role::SECTOR_LEADER_ROLE) {
            return parent::getTableQuery()->where('leader_id', Auth::user()->leader->id);
        }
    }
}
