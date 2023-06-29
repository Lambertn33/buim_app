<?php

namespace App\Filament\Widgets\Dashboard\Leader;

use App\Models\Role;
use App\Services\Dashboard\Leader\DistributionsDashboardServices;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class LatestDistributions extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 4;

    protected function getTableQuery(): Builder
    {
        return (new DistributionsDashboardServices)->getLatestDistributions();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('contract_id')
                ->sortable()
                ->label('Contract ID'),
            TextColumn::make('screener.prospect_names')
                ->label('Customer names')
                ->sortable(),
            TextColumn::make('warehouseDevice.device_name')
                ->label('Device name')
                ->sortable(),
            TextColumn::make('warehouseDevice.serial_number')
                ->label('Device Serial number')
                ->sortable()
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->role->role === Role::SECTOR_LEADER_ROLE;
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
