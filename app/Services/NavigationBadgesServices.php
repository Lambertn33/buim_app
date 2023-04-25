<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\PaymentPlan;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Screening;
use App\Models\StockModel;
use App\Models\SubStockRequest;
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

    public function getTotalNumberOfScreenings()
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return Screening::count();
        } elseif (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return Screening::where('manager_id', Auth::user()->manager->id)->count();
        } else {
            return Screening::where('leader_id', Auth::user()->leader->id)->count();
        }
    }

    public function getTotalNumberOfDeviceModels()
    {
        return StockModel::count();
    }
}
