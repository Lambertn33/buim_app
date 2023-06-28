<?php

namespace App\Services\Dashboard;

use App\Models\Screening;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class ScreeningsDashboardServices
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
    public function getLatestScreenings()
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return Screening::latest()->limit(5);
        } elseif (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return Screening::where('district', Auth::user()->manager->district->district)->latest()->limit(5);
        } else {
            return Screening::where('leader_id', Auth::user()->leader->id)->latest()->limit(5);
        }
    }

    public function getTotalNumberOfScreeningsPerMonth($month)
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return Screening::whereMonth('created_at', $month)->whereYear('created_at', date('Y'))->count();
        } elseif (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return Screening::whereMonth('created_at', $month)->whereYear('created_at', date('Y'))->where('district', Auth::user()->manager->district->district)->count();
        } else {
            return Screening::whereMonth('created_at', $month)->whereYear('created_at', date('Y'))->where('leader_id', Auth::user()->leader->id)->count();
        }
    }
}
