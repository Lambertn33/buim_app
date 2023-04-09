<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Illuminate\Support\Str;

class rolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->delete();
        $roles = [];
        foreach (Role::ROLES as $role) {
            $roles[] = [
                'id' => Str::uuid()->toString(),
                'role' => $role,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        Role::insert($roles);
    }
}
