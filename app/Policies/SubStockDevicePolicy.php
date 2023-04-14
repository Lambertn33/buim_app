<?php

namespace App\Policies;

use App\Models\SubStockDevice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubStockDevicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('sub_stock_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubStockDevice $subStockDevice): bool
    {
        return $user->hasPermission('sub_stock_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('sub_stock_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubStockDevice $subStockDevice): bool
    {
        return $user->hasPermission('sub_stock_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('sub_stock_delete');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubStockDevice $subStockDevice): bool
    {
        return $user->hasPermission('sub_stock_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubStockDevice $subStockDevice): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubStockDevice $subStockDevice): bool
    {
        //
    }
}
