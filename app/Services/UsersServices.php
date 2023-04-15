<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use App\Models\Leader;
use App\Models\Manager;
use App\Models\Manufacturer;
use App\Models\StockManager;

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
            'sub_stock_show',
            'sub_stock_create',
            'sub_stock_create',
            'sub_stock_edit',
            'sub_stock_delete',
            'sub_stock_request_create',
            'sub_stock_request_delete'
        ])->get();

        $managerPermissions = Permission::whereIn('permission', [
            'campaign_create',
            'campaign_access',
            'campaign_show',
            'campaign_edit',
            'campaign_delete',
            'stock_model_access',
            'sub_stock_access',
            'sub_stock_show',
            'sub_stock_edit',
            'sub_stock_delete',
            'screening_access',
            'sub_stock_request_create',
            'sub_stock_request_access',
            'sub_stock_request_show',
            'sub_stock_request_delete',
        ])->get();

        $leaderPermissions = Permission::whereIn('permission', [
            'screening_access',
            'screening_create',
            'screening_show',
            'screening_edit',
            'screening_delete'
        ])->get();

        $stockManagerPermissions = Permission::whereIn('permission', [
            'sub_stock_access',
            'sub_stock_show',
            'sub_stock_request_access',
            'sub_stock_request_show',
            'sub_stock_request_edit',
            'stock_pending_access',
            'stock_create',
            'stock_access',
            'stock_show',
            'stock_edit',
            'stock_delete',
        ])->get();

        $manufacturerPermissions = Permission::whereIn('permission', [
            'stock_pending_create',
            'stock_pending_access',
            'stock_pending_show',
            'stock_pending_edit',
            'stock_pending_delete',
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
            } elseif ($user->role->role == Role::SECTOR_LEADER_ROLE) {
                $user->permissions()->sync($leaderPermissions);
                Leader::insert($newNonAdmin);
            } elseif ($user->role->role == Role::STOCK_MANAGER_ROLE) {
                $user->permissions()->sync($stockManagerPermissions);
                StockManager::insert($newNonAdmin);
            } elseif($user->role->role == Role::MANUFACTURER_ROLE) {
                $user->permissions()->sync($manufacturerPermissions);
                Manufacturer::insert($newNonAdmin);
            }
        }
    }
}
