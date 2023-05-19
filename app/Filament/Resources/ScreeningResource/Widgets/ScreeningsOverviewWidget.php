<?php

namespace App\Filament\Resources\ScreeningResource\Widgets;

use App\Models\Role;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;
use App\Models\Screening;

class ScreeningsOverviewWidget extends BaseWidget
{
    protected function getCards(): array
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return [
                Card::make('Total Screenings', Screening::count()),
                Card::make('Total Eligible Screenings', Screening::where('eligibility_status', Screening::ELIGIBLE)->count()),
                Card::make('Total Non-eligible Screenings', Screening::where('eligibility_status', Screening::NOT_ELIGIBLE)->count())
            ];
        } elseif(Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            $authManagerDistrict = Auth::user()->manager->district->district;
            $authManagerId = Auth::user()->manager->id;
            return [
                Card::make('Screenings in '.$authManagerDistrict.' District' , Screening::whereHas('campaign', function($query){
                    $query->where('district_id', Auth::user()->manager->district->id);
                })->count()),
                Card::make('Eligible Screenings in '.$authManagerDistrict.' District', Screening::where('eligibility_status', Screening::ELIGIBLE)->whereHas('campaign', function($query){
                    $query->where('district_id', Auth::user()->manager->district->id);
                })->count()),
                Card::make('Non-eligible Screenings in '.$authManagerDistrict.' District', Screening::where('eligibility_status', Screening::NOT_ELIGIBLE)->whereHas('campaign', function($query){
                    $query->where('district_id', Auth::user()->manager->district->id);
                })->count())
            ];
        } else {
            $authLeaderId = Auth::user()->leader->id;
            return [
                Card::make('Total Screenings', Screening::where('leader_id', $authLeaderId)->count()),
                Card::make('Total Eligible Screenings', Screening::where('eligibility_status', Screening::ELIGIBLE)->where('leader_id', $authLeaderId)->count()),
                Card::make('Total Non-eligible Screenings', Screening::where('eligibility_status', Screening::NOT_ELIGIBLE)->where('leader_id', $authLeaderId)->count())
            ];

        }
    }
}
