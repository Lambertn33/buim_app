<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use Illuminate\Support\Str;

class permissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->delete();
        $roles = [];
        foreach (Permission::PERMISSIONS as $permission) {
            $roles[] = [
                'id' => Str::uuid()->toString(),
                'permission' => $permission,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        Permission::insert($roles);
    }
}
