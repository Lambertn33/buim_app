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
            $authManagerDistrict = Auth::user()->manager->district;
            return [
                Card::make('On Going Campaigns in '.$authManagerDistrict->district.' District', Campaign::where('status', Campaign::ONGOING)->where('district_id', $authManagerDistrict->id)->count()),
                Card::make('Completed Campaigns in '.$authManagerDistrict->district.' District', Campaign::where('status', Campaign::FINISHED)->where('district_id', $authManagerDistrict->id)->count())
            ];
        }
    }
}
