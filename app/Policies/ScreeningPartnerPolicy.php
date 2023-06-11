<?php

namespace App\Policies;

use App\Models\ScreeningPartner;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScreeningPartnerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('screening_partner_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ScreeningPartner $screeningPartner): bool
    {
        return $user->hasPermission('screening_partner_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('screening_partner_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ScreeningPartner $screeningPartner): bool
    {
        return $user->hasPermission('screening_partner_edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ScreeningPartner $screeningPartner): bool
    {
        return $user->hasPermission('screening_partner_delete');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('screening_partner_delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ScreeningPartner $screeningPartner): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ScreeningPartner $screeningPartner): bool
    {
        //
    }
}
