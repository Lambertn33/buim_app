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
            $authManagerId = Auth::user()->manager->id;
            return [
                Card::make('Total Screenings', Screening::where('manager_id', $authManagerId)->count()),
                Card::make('Total Eligible Screenings', Screening::where('eligibility_status', Screening::ELIGIBLE)->where('manager_id', $authManagerId)->count()),
                Card::make('Total Non-eligible Screenings', Screening::where('eligibility_status', Screening::NOT_ELIGIBLE)->where('manager_id', $authManagerId)->count())
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
