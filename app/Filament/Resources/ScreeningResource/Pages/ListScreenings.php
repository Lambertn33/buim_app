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
                ->disabled(Campaign::whereHas('district', function($query) {
                    $query->where('id', Auth::user()->leader->district_id);
                })->count() < 1 || PaymentPlan::count() < 1),
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
            return parent::getTableQuery()->where('manager_id', Auth::user()->manager->id);
        } elseif(Auth::user()->role->role === Role::SECTOR_LEADER_ROLE) {
            return parent::getTableQuery()->where('leader_id', Auth::user()->leader->id);
        }
    }
}
