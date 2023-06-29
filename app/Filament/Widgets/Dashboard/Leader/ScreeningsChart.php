<?php

namespace App\Filament\Widgets\Dashboard\Leader;

use App\Models\Role;
use App\Services\Dashboard\Leader\ScreeningsDashboardServices;
use Filament\Widgets\BarChartWidget;

class ScreeningsChart extends BarChartWidget
{
    protected static ?string $heading = 'Screenings per month';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Screenings per month',
                    'data' => [
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('01'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('02'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('03'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('04'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('05'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('06'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('07'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('08'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('09'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('10'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('11'),
                        (new ScreeningsDashboardServices)->getTotalNumberOfScreeningsPerMonth('12')
                    ],
                    'backgroundColor' => [
                        '#ff9999',
                        '#ccffff',
                        'yellow',
                        '#66d9ff',
                        '#c6ffb3',
                        '#66b3ff',
                        '#ff1a1a',
                        '#000066',
                        '#ffaa00',
                        '#ff66b3',
                        '#800040',
                        '#804000'
                    ],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role->role === Role::SECTOR_LEADER_ROLE;
    }
}
