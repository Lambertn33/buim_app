<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class userPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_permissions')->delete();

        $admins = User::whereHas('role', function ($query) {
            $query->where('role', Role::ADMIN_ROLE);
        })->get();
        $managers = User::whereHas('role', function ($query) {
            $query->where('role', Role::DISTRICT_MANAGER_ROLE);
        })->get();
        $leaders = User::whereHas('role', function ($query) {
            $query->where('role', Role::SECTOR_LEADER_ROLE);
        })->get();

        $stockManagers = User::whereHas('role', function ($query) {
            $query->where('role', Role::STOCK_MANAGER_ROLE);
        })->get();

        $manufacturers = User::whereHas('role', function ($query) {
            $query->where('role', Role::MANUFACTURER_ROLE);
        })->get();

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
            'technician_edit',
            'technician_create',
            'technician_delete',
            'distribution_create',
        ])->get();

        $managerPermissions = Permission::whereIn('permission', [
            'campaign_create',
            'campaign_access',
            'campaign_show',
            'campaign_edit',
            'campaign_delete',
            'stock_model_access',
            'screening_access',
            'screening_show',
            'warehouse_access',
            'warehouse_show',
            'warehouse_edit',
            'warehouse_create',
            'warehouse_delete',
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
            'technician_access',
            'technician_show',
            'technician_edit',
            'technician_create',
            'technician_delete',
            'distribution_access',
            'distribution_show',
        ])->get();

        $leaderPermissions = Permission::whereIn('permission', [
            'screening_access',
            'screening_create',
            'screening_show',
            'screening_edit',
            'screening_delete',
            'warehouse_device_access',
            'technician_access',
            'distribution_access',
            'distribution_show',
            'distribution_create',
        ])->get();

        $stockManagerPermissions = Permission::whereIn('permission', [
            'stock_create',
            'stock_access',
            'stock_show',
            'stock_edit',
            'stock_delete',
            'main_warehouse_access',
            'main_warehouse_show',
            'main_warehouse_edit',
            'main_warehouse_create',
            'main_warehouse_delete',
            'warehouse_access',
            'warehouse_show',
            'warehouse_edit',
            'warehouse_create',
            'warehouse_delete',
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



        foreach ($admins as $admin) {
            $admin->permissions()->sync($adminPermissions);
        }
        foreach ($leaders as $leader) {
            $leader->permissions()->sync($leaderPermissions);
        }
        foreach ($managers as $manager) {
            $manager->permissions()->sync($managerPermissions);
        }

        foreach ($stockManagers as $stockManager) {
            $stockManager->permissions()->sync($stockManagerPermissions);
        }

        foreach ($manufacturers as $manufacturer) {
            $manufacturer->permissions()->sync($manufacturerPermissions);
        }
    }
}
