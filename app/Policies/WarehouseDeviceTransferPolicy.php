<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WarehouseDeviceTransfer;
use Illuminate\Auth\Access\Response;

class WarehouseDeviceTransferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('warehouse_device_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WarehouseDeviceTransfer $warehouseDeviceTransfer): bool
    {
        return $user->hasPermission('warehouse_device_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('warehouse_device_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WarehouseDeviceTransfer $warehouseDeviceTransfer): bool
    {
        return $user->hasPermission('warehouse_device_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('warehouse_device_delete');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WarehouseDeviceTransfer $warehouseDeviceTransfer): bool
    {
        return $user->hasPermission('warehouse_device_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WarehouseDeviceTransfer $warehouseDeviceTransfer): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WarehouseDeviceTransfer $warehouseDeviceTransfer): bool
    {
        //
    }
}
