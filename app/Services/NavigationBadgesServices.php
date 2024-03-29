<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\DevicePrice;
use App\Models\MainWarehouse;
use App\Models\MainWarehouseDevice;
use App\Models\PaymentPlan;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Screening;
use App\Models\ScreeningPartner;
use App\Models\StockModel;
use App\Models\SubStockRequest;
use App\Models\Technician;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseDevice;
use App\Models\WarehouseDeviceDistribution;
use App\Models\WarehouseDeviceTransfer;
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
            return Campaign::whereHas('district', function($query) {
                $query->where('district', Auth::user()->manager->district->district);
            })->count();
        }
    }

    public function getTotalNumberOfScreenings()
    {
        if (Auth::user()->role->role === Role::ADMIN_ROLE) {
            return Screening::count();
        } elseif (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return Screening::where('district', Auth::user()->manager->district->district)->count();
        } else {
            return Screening::where('leader_id', Auth::user()->leader->id)->count();
        }
    }

    public function getTotalNumberOfDeviceModels()
    {
        return StockModel::count();
    }

    public function getTotalNumberOfMainWarehouses()
    {
        return MainWarehouse::count();
    }
    public function getTotalNumberOfScreeningPartners()
    {
        return ScreeningPartner::count();
    }

    public function getTotalNumberOfDevicePrices()
    {
        return DevicePrice::count();
    }

    public function getTotalNumberOfHQWarehouseDevices()
    {
        return MainWarehouseDevice::whereHas('mainWarehouse', function ($query) {
            $query->where('name', MainWarehouse::HQWAREHOUSE);
        })->count();
    }

    public function getTotalNumberOfDPWorldWarehouseDevices()
    {
        if (Auth::user()->role->role == Role::MANUFACTURER_ROLE) {
            $manufacturerId = Auth::user()->manufacturer->id;
            return MainWarehouseDevice::where('is_approved', true)->where('initialized_by', $manufacturerId)->whereHas('mainWarehouse', function ($query) {
                $query->where('name', MainWarehouse::DPWORLDWAREHOUSE);
            })->count();
        } else {
            return MainWarehouseDevice::where('is_approved', true)->whereHas('mainWarehouse', function ($query) {
                $query->where('name', MainWarehouse::DPWORLDWAREHOUSE);
            })->count();
        }
    }

    public function getTotalNumberOfPendingDPWorldWarehouseDevices()
    {
        if (Auth::user()->role->role == Role::MANUFACTURER_ROLE) {
            $manufacturerId = Auth::user()->manufacturer->id;
            return MainWarehouseDevice::where('is_approved', false)->where('initialized_by', $manufacturerId)->whereHas('mainWarehouse', function ($query) {
                $query->where('name', MainWarehouse::DPWORLDWAREHOUSE);
            })->count();
        } else {
            return MainWarehouseDevice::where('is_approved', false)->whereHas('mainWarehouse', function ($query) {
                $query->where('name', MainWarehouse::DPWORLDWAREHOUSE);
            })->count();
        }
    }

    public function getTotalNumberOfRugandoWarehouseDevices()
    {
        return MainWarehouseDevice::whereHas('mainWarehouse', function ($query) {
            $query->where('name', MainWarehouse::RUGANDOWAREHOUSE);
        })->count();
    }

    public function getTotalNumberOfWarehouses()
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return Warehouse::where('district_id', Auth::user()->manager->district->id)->count();
        } else {
            return Warehouse::count();
        }
    }

    public function getTotalNumberOfWarehouseDevices()
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return WarehouseDevice::where('district_id', Auth::user()->manager->district->id)->count();
        } else if (Auth::user()->role->role === Role::SECTOR_LEADER_ROLE) {
            return WarehouseDevice::where('district_id', Auth::user()->leader->district->id)->where('warehouse_id', Auth::user()->leader->warehouse_id)->count();
        } else {
            return WarehouseDevice::count();
        }
    }

    public function getTotalNumberOfWarehouseDeviceTransfers()
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return WarehouseDeviceTransfer::whereHas('sender', function($query) {
                $query->where('district_id', Auth::user()->manager->district->id);
            })->orWhereHas('receiver', function($query) {
                $query->where('district_id', Auth::user()->manager->district->id);
            })->count();
        } else {
            return WarehouseDeviceTransfer::count();
        }
    }

    public function getTotalNumberOfTechnicians()
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            return Technician::where('district_id', Auth::user()->manager->district->id)->count();
        } else if(Auth::user()->role->role === Role::SECTOR_LEADER_ROLE){
            return Technician::where('district_id', Auth::user()->leader->district->id)->count(); 
        } else {
            return Technician::count();
        }
    }

    public function getTotalNumberOfDistributions()
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) 
        {
            return WarehouseDeviceDistribution::whereHas('warehouseDevice', function($query) {
                return $query->where('district_id', Auth::user()->manager->district->id);
            })->count();
        }else if(Auth::user()->role->role === Role::SECTOR_LEADER_ROLE) {
            return WarehouseDeviceDistribution::whereHas('warehouseDevice', function($query) {
                return $query->where('district_id', Auth::user()->leader->district->id);
            })->count();
        } else {
            return WarehouseDeviceDistribution::count();
        }
    }
}
