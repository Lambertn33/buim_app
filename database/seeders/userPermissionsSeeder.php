<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class userPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_permissions')->delete();

        $defaultAdmin = User::where('email', 'admin@gmail.com')->first();
        $defaultManager = User::where('email', 'manager@gmail.com')->first();
        $defaultLeader = User::where('email', 'leader@gmail.com')->first();

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

        $defaultAdmin->permissions()->sync($adminPermissions);
        $defaultManager->permissions()->sync($managerPermissions);
        $defaultLeader->permissions()->sync($leaderPermissions);
    }
}
