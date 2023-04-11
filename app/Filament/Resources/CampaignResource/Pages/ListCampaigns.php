<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use App\Filament\Resources\CampaignResource\Widgets\CampaignsOverviewWidget;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class ListCampaigns extends ListRecords
{
    protected static string $resource = CampaignResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
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
        : parent::getTableQuery()->where('manager_id', Auth::user()->manager->id);
    }
}
