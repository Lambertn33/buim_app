<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Screening;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Services\DashboardServices;


class ScreeningsCount extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Prospect customers', (new DashboardServices)->getTotalNumberOfScreenings(Screening::PROSPECT))
                ->description('total prospect customers')
                ->chart([3,4,1,2,4,1])
                ->color('danger')
                ->descriptionIcon('heroicon-o-user-group'),
            Card::make('Pre-registered customers', (new DashboardServices)->getTotalNumberOfScreenings(Screening::PRE_REGISTERED))
                ->description('total pre-registered customers')
                ->chart([4,1,3,4,2,1])
                ->color('warning')
                ->descriptionIcon('heroicon-o-user-group'),
            Card::make('Active customers', (new DashboardServices)->getTotalNumberOfScreenings(Screening::ACTIVE_CUSTOMER))
                ->description('total active customers')
                ->chart([5,4,3,1,2,1])
                ->color('success')
                ->descriptionIcon('heroicon-o-user-group'),
        ];
    }
}
