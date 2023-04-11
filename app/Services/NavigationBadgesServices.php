<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\PaymentPlan;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NavigationBadgesServices
{
    public function getTotalNumberOfUsers()
    {
        return User::count();
    } 

    public function getTotalNumberOfRoles()
    {
        return Role::count();
    }

    public function getTotalNumberOfPaymentPlans()
    {
        return PaymentPlan::count();
    }

    public function getTotalNumberOfPermissions()
    {
        return Permission::count();
    }
    
    public function getTotalNumberOfCampaigns()
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return Campaign::count();
        } else {
            return Campaign::where('manager_id', Auth::user()->manager->id)->count();
        }
    }
}
