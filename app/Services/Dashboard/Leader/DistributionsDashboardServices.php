<?php

namespace App\Services\Dashboard\Leader;

use Illuminate\Support\Facades\Auth;
use App\Models\WarehouseDeviceDistribution;

class DistributionsDashboardServices
{
    public function getTotalNumberOfDistributions()
    {
        return WarehouseDeviceDistribution::whereHas('warehouseDevice', function($query) {
            return $query->where('district_id', Auth::user()->leader->district->id);
        })->count();
    }

    public function getLatestDistributions()
    {
        return WarehouseDeviceDistribution::whereHas('warehouseDevice', function($query) {
            return $query->where('district_id', Auth::user()->leader->district->id);
        })->latest()->limit(5);
    }
}
