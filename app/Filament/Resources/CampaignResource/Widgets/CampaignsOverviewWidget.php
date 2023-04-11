<?php

namespace App\Filament\Resources\CampaignResource\Widgets;

use App\Models\Campaign;
use App\Models\Role;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class CampaignsOverviewWidget extends BaseWidget
{
    protected function getCards(): array
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return [
                Card::make('Total Campaigns', Campaign::count()),
                Card::make('On Going Campaigns', Campaign::where('status', Campaign::ONGOING)->count()),
                Card::make('Closed Campaigns', Campaign::where('status', Campaign::STOPPED)->count()),
                Card::make('Completed Campaigns', Campaign::where('status', Campaign::FINISHED)->count()),
            ];
        } else {
            $authManagerId = Auth::user()->manager->id;
            return [
                Card::make('My On Going Campaigns', Campaign::where('status', Campaign::ONGOING)->where('manager_id', $authManagerId)->count()),
                Card::make('My Completed Campaigns', Campaign::where('status', Campaign::FINISHED)->where('manager_id', $authManagerId)->count())
            ];
        }
    }
}
