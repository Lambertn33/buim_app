<?php

namespace App\Policies;

use App\Models\PaymentPlan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPlanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('payment_plan_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentPlan $paymentPlan): bool
    {
        return $user->hasPermission('payment_plan_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('payment_plan_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentPlan $paymentPlan): bool
    {
        return $user->hasPermission('payment_plan_edit');
    }

    /**
     * Determine whether the user can bulk delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('payment_plan_delete');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentPlan $paymentPlan): bool
    {
        return $user->hasPermission('payment_plan_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentPlan $paymentPlan): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentPlan $paymentPlan): bool
    {
        //
    }
}
