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

        $admins = User::whereHas('role', function($query){
            $query->where('role', Role::ADMIN_ROLE);
        })->get();
        $managers = User::whereHas('role', function($query){
            $query->where('role', Role::DISTRICT_MANAGER_ROLE);
        })->get();
        $leaders = User::whereHas('role', function($query){
            $query->where('role', Role::SECTOR_LEADER_ROLE);
        })->get();

        $stockManagers = User::whereHas('role', function($query){
            $query->where('role', Role::STOCK_MANAGER_ROLE);
        })->get();

        $adminPermissions = Permission::whereNotIn('permission', [
            'campaign_create',
            'campaign_edit',
            'campaign_delete',
            'screening_create',
            'screening_edit',
            'screening_delete',
            'sub_stock_create',
            'sub_stock_edit',
            'sub_stock_delete',
        ])->get();

        $managerPermissions = Permission::whereIn('permission', [
            'campaign_create',
            'campaign_access',
            'campaign_show',
            'campaign_edit',
            'campaign_delete',
            'sub_stock_create',
            'sub_stock_access',
            'sub_stock_show',
            'sub_stock_edit',
            'sub_stock_delete',
            'screening_access',
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
            'stock_create',
            'stock_access',
            'stock_show',
            'stock_edit',
            'stock_delete',
        ])->get();



        foreach( $admins as $admin) {
            $admin->permissions()->sync($adminPermissions);
        }
        foreach( $leaders as $leader) {
            $leader->permissions()->sync($leaderPermissions);
        }
        foreach( $managers as $manager) {
            $manager->permissions()->sync($managerPermissions);
        }

        foreach ($stockManagers as $stockManager) {
            $stockManager->permissions()->sync($stockManagerPermissions);
        }
    }
}
