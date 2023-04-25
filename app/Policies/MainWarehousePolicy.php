<?php

namespace App\Policies;

use App\Models\MainWarehouse;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MainWarehousePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('main_warehouse_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MainWarehouse $mainWarehouse): bool
    {
        return $user->hasPermission('main_warehouse_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('main_warehouse_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MainWarehouse $mainWarehouse): bool
    {
        return $user->hasPermission('main_warehouse_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('main_warehouse_delete');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MainWarehouse $mainWarehouse): bool
    {
        return $user->hasPermission('main_warehouse_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MainWarehouse $mainWarehouse): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MainWarehouse $mainWarehouse): bool
    {
        //
    }
}
