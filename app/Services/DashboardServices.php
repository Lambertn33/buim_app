<?php

namespace App\Services;

use App\Models\Screening;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class DashboardServices
{
    public function getTotalNumberOfScreenings($confirmationStatus)
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return Screening::where('confirmation_status', $confirmationStatus)->count();
        } elseif (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return Screening::where('confirmation_status', $confirmationStatus)->where('district', Auth::user()->manager->district->district)->count();
        } else {
            return Screening::where('confirmation_status', $confirmationStatus)->where('leader_id', Auth::user()->leader->id)->count();
        }
    }
}
