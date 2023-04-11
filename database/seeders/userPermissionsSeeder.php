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

        $adminPermissions = Permission::whereNotIn('permission', [
            'campaign_create',
            'campaign_edit',
            'campaign_delete',
            'screening_create',
            'screening_edit',
            'screening_delete',
        ])->get();

        $managerPermissions = Permission::whereIn('permission', [
            'campaign_create',
            'campaign_access',
            'campaign_show',
            'campaign_edit',
            'campaign_delete',
            'stock_create',
            'stock_access',
            'stock_show',
            'stock_edit',
            'stock_delete',
            'screening_access',
        ])->get();

        $leaderPermissions = Permission::whereIn('permission', [
            'screening_access',
            'screening_create',
            'screening_show',
            'screening_edit',
            'screening_delete'
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
    }
}
