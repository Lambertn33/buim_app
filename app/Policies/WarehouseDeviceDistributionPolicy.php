<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WarehouseDeviceDistribution;
use Illuminate\Auth\Access\Response;

class WarehouseDeviceDistributionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('distribution_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WarehouseDeviceDistribution $warehouseDeviceDistribution): bool
    {
        return $user->hasPermission('distribution_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('distribution_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WarehouseDeviceDistribution $warehouseDeviceDistribution): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WarehouseDeviceDistribution $warehouseDeviceDistribution): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WarehouseDeviceDistribution $warehouseDeviceDistribution): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WarehouseDeviceDistribution $warehouseDeviceDistribution): bool
    {
        //
    }
}
