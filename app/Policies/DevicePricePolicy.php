<?php

namespace App\Policies;

use App\Models\DevicePrice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DevicePricePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('device_price_access');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DevicePrice $devicePrice): bool
    {
        return $user->hasPermission('device_price_show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('device_price_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DevicePrice $devicePrice): bool
    {
        return $user->hasPermission('device_price_edit');
    }

}
