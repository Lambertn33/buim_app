<?php

namespace App\Services;

use App\Models\District;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use App\Models\Leader;
use App\Models\Manager;
use App\Models\Manufacturer;
use App\Models\StockManager;
use Illuminate\Support\Facades\Session;

class UsersServices
{
    public function createUser($user)
    {
        $adminPermissions = Permission::whereNotIn('permission', [
            'campaign_create',
            'campaign_edit',
            'campaign_delete',
            'screening_create',
            'screening_edit',
            'screening_delete',
            'warehouse_device_edit',
            'warehouse_device_create',
            'warehouse_device_delete',
            'warehouse_device_request_edit',
            'warehouse_device_request_create',
            'warehouse_device_request_delete',
        ])->get();

        $managerPermissions = Permission::whereIn('permission', [
            'campaign_create',
            'campaign_access',
            'campaign_show',
            'campaign_edit',
            'campaign_delete',
            'warehouse_access',
            'warehouse_show',
            'warehouse_edit',
            'warehouse_create',
            'warehouse_delete',
            'stock_model_access',
            'screening_access',
            'warehouse_device_access',
            'warehouse_device_show',
            'warehouse_device_edit',
            'warehouse_device_create',
            'warehouse_device_delete',
            'warehouse_device_request_access',
            'warehouse_device_request_show',
            'warehouse_device_request_edit',
            'warehouse_device_request_create',
            'warehouse_device_request_delete',
        ])->get();

        $leaderPermissions = Permission::whereIn('permission', [
            'screening_access',
            'screening_create',
            'screening_show',
            'screening_edit',
            'screening_delete'
        ])->get();

        $stockManagerPermissions = Permission::whereIn('permission', [
            'stock_create',
            'stock_access',
            'stock_show',
            'stock_edit',
            'stock_delete',
            'warehouse_access',
            'warehouse_show',
            'warehouse_edit',
            'warehouse_create',
            'warehouse_delete',
            'main_warehouse_access',
            'main_warehouse_show',
            'main_warehouse_edit',
            'main_warehouse_create',
            'main_warehouse_delete',
            'warehouse_device_access',
            'warehouse_device_show',
            'warehouse_device_request_access',
            'warehouse_device_request_show',
            'warehouse_device_request_edit',
            'warehouse_device_request_create',
            'warehouse_device_request_delete',
        ])->get();

        $manufacturerPermissions = Permission::whereIn('permission', [
            'stock_model_access',
        ])->get();

        // After user is created in dashboard, Add respective permissions

        if ($user->role->role == Role::ADMIN_ROLE) {
            $user->permissions()->sync($adminPermissions);
        } else {
            $newNonAdmin = [
                'id' => Str::uuid()->toString(),
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            if ($user->role->role == Role::DISTRICT_MANAGER_ROLE) {
                $user->permissions()->sync($managerPermissions);
                Manager::insert($newNonAdmin);
                if (Session::has('district_id')) {
                    $districtId = Session::get('district_id');
                    $district = District::find($districtId);
                    $district->update([
                        'manager_id' => $newNonAdmin['id']
                    ]);
                }
            } elseif ($user->role->role == Role::SECTOR_LEADER_ROLE) {
                $user->permissions()->sync($leaderPermissions);
                Leader::insert($newNonAdmin);
            } elseif ($user->role->role == Role::STOCK_MANAGER_ROLE) {
                $user->permissions()->sync($stockManagerPermissions);
                StockManager::insert($newNonAdmin);
            } elseif ($user->role->role == Role::MANUFACTURER_ROLE) {
                $user->permissions()->sync($manufacturerPermissions);
                Manufacturer::insert($newNonAdmin);
            }
        }
    }
}
