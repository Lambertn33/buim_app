<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Role;
use App\Models\Screening;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Services\Dashboard\ScreeningsDashboardServices;


class ScreeningsCount extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            Card::make('Prospect customers', (new ScreeningsDashboardServices)->getTotalNumberOfScreenings(Screening::PROSPECT))
                ->description('total prospect customers')
                ->chart([3,4,1,2,4,1])
                ->color('danger')
                ->descriptionIcon('heroicon-o-user-group'),
            Card::make('Pre-registered customers', (new ScreeningsDashboardServices)->getTotalNumberOfScreenings(Screening::PRE_REGISTERED))
                ->description('total pre-registered customers')
                ->chart([4,1,3,4,2,1])
                ->color('warning')
                ->descriptionIcon('heroicon-o-user-group'),
            Card::make('Active customers', (new ScreeningsDashboardServices)->getTotalNumberOfScreenings(Screening::ACTIVE_CUSTOMER))
                ->description('total active customers')
                ->chart([5,4,3,1,2,1])
                ->color('success')
                ->descriptionIcon('heroicon-o-user-group'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role->role === Role::ADMIN_ROLE || auth()->user()->role->role === Role::DISTRICT_MANAGER_ROLE || auth()->user()->role->role === Role::SECTOR_LEADER_ROLE;
    }
}
