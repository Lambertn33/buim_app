<?php

namespace App\Filament\Widgets\Dashboard\Leader;

use App\Models\Role;
use App\Models\Screening;
use App\Services\Dashboard\Leader\DistributionsDashboardServices;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Services\Dashboard\Leader\ScreeningsDashboardServices;


class ScreeningsCount extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            Card::make('Pre-registered customers', (new ScreeningsDashboardServices)->getTotalNumberOfScreenings(Screening::PRE_REGISTERED))
                ->description('Total pre-registered customers')
                ->chart([4, 1, 3, 4, 2, 1])
                ->color('warning')
                ->descriptionIcon('heroicon-o-user-group'),
            Card::make('Active customers', (new ScreeningsDashboardServices)->getTotalNumberOfScreenings(Screening::ACTIVE_CUSTOMER))
                ->description('Total active customers')
                ->chart([5, 4, 3, 1, 2, 1])
                ->color('success')
                ->descriptionIcon('heroicon-o-user-group'),
            Card::make('Distributions', (new DistributionsDashboardServices)->getTotalNumberOfDistributions())
                ->description('Total distributions')
                ->chart([1, 4, 5, 1, 3, 1])
                ->color('info')
                ->descriptionIcon('heroicon-o-share'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role->role === Role::SECTOR_LEADER_ROLE;
    }
}
