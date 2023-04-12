<?php

namespace App\Policies;

use App\Models\StockModel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockModelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('stock_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StockModel $stockModel): bool
    {
        return $user->hasPermission('stock_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('stock_show');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StockModel $stockModel): bool
    {
        return $user->hasPermission('stock_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('stock_delete');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StockModel $stockModel): bool
    {
        return $user->hasPermission('stock_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StockModel $stockModel): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StockModel $stockModel): bool
    {
        //
    }
}
