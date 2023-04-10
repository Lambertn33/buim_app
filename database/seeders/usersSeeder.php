<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Leader;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        $users = [
            [
                'id' => Str::uuid()->toString(),
                'name' => 'administrator',
                'role_id' => Role::where('role', Role::ADMIN_ROLE)->value('id'),
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin12345'),
                'telephone' => '250788000000',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'district manager',
                'role_id' => Role::where('role', Role::DISTRICT_MANAGER_ROLE)->value('id'),
                'email' => 'manager@gmail.com',
                'password' => Hash::make('manager12345'),
                'telephone' => '250788000011',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'sector leader',
                'email' => 'leader@gmail.com',
                'role_id' => Role::where('role', Role::SECTOR_LEADER_ROLE)->value('id'),
                'password' => Hash::make('leader12345'),
                'telephone' => '250788000022',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        User::insert($users);

        foreach (User::with('role')->get() as $user) {
            if ($user->role->role == Role::DISTRICT_MANAGER_ROLE) {
                $newManager = [
                    'id' => Str::uuid()->toString(),
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                Manager::insert($newManager);
            }
            if ($user->role->role == Role::SECTOR_LEADER_ROLE) {
                $newLeader = [
                    'id' => Str::uuid()->toString(),
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                Leader::insert($newLeader);
            }
        }
    }
}
