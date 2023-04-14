<?php

namespace App\Policies;

use App\Models\SubStockRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubStockRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('sub_stock_request_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubStockRequest $subStockRequest): bool
    {
        return $user->hasPermission('sub_stock_request_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('sub_stock_request_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubStockRequest $subStockRequest): bool
    {
        return $user->hasPermission('sub_stock_request_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('sub_stock_request_delete');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubStockRequest $subStockRequest): bool
    {
        return $user->hasPermission('sub_stock_request_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubStockRequest $subStockRequest): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubStockRequest $subStockRequest): bool
    {
        //
    }
}
