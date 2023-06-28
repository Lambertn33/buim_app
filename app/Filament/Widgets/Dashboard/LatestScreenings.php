<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Role;
use App\Models\Screening;
use App\Services\Dashboard\ScreeningsDashboardServices;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestScreenings extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    protected function getTableQuery(): Builder
    {
        return (new ScreeningsDashboardServices)->getLatestScreenings();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('prospect_names')->sortable(),
            Tables\Columns\TextColumn::make('prospect_telephone')
                ->label('telephone')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('prospect_code')
                ->label('code')
                ->sortable()
                ->searchable(),
            Tables\Columns\BadgeColumn::make('confirmation_status')
                ->sortable()
                ->colors([
                    'danger' => static fn ($state): bool => $state === Screening::PROSPECT,
                    'warning' => static fn ($state): bool => $state === Screening::PRE_REGISTERED,
                    'success' => static fn ($state): bool => $state === Screening::ACTIVE_CUSTOMER,
                ]),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role->role === Role::ADMIN_ROLE
            || auth()->user()->role->role === Role::DISTRICT_MANAGER_ROLE
            || auth()->user()->role->role === Role::SECTOR_LEADER_ROLE;
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
