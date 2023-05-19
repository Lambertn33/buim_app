<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use App\Filament\Resources\CampaignResource\Widgets\CampaignsOverviewWidget;
use App\Models\Campaign;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Closure;

class ListCampaigns extends ListRecords
{
    protected static string $resource = CampaignResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(function () {
                    if (Auth::user()->role->role == Role::DISTRICT_MANAGER_ROLE) {
                        $authenticatedManager = Auth::user()->manager;
                        if (Campaign::where('manager_id', $authenticatedManager->id)
                            ->where('status', Campaign::ONGOING)->exists()
                        ) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                }),
        ];
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Campaign $record) => Auth::user()->role->role !== Role::DISTRICT_MANAGER_ROLE ? null : (
            $record->manager_id == Auth::user()->manager->id ? route('filament.resources.campaigns.edit', ['record' => $record]) : null
        );
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CampaignsOverviewWidget::class
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Auth::user()->role->role === Role::ADMIN_ROLE
            ? parent::getTableQuery()
            : parent::getTableQuery()->whereHas('district', function ($query) {
                $query->where('district', Auth::user()->manager->district->district);
            });
    }
}
