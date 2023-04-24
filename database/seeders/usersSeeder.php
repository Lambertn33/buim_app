<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Leader;
use App\Models\Manufacturer;
use App\Models\StockManager;
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
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'district manager 2',
                'role_id' => Role::where('role', Role::DISTRICT_MANAGER_ROLE)->value('id'),
                'email' => 'manager2@gmail.com',
                'password' => Hash::make('manager12345'),
                'telephone' => '250788000013',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'sector leader 2',
                'email' => 'leader2@gmail.com',
                'role_id' => Role::where('role', Role::SECTOR_LEADER_ROLE)->value('id'),
                'password' => Hash::make('leader12345'),
                'telephone' => '250788000025',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'stock manager',
                'email' => 'stockManager@gmail.com',
                'role_id' => Role::where('role', Role::STOCK_MANAGER_ROLE)->value('id'),
                'password' => Hash::make('stockManager12345'),
                'telephone' => '250788000029',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manufacturer',
                'email' => 'manufacturer@gmail.com',
                'role_id' => Role::where('role', Role::MANUFACTURER_ROLE)->value('id'),
                'password' => Hash::make('manufacturer12345'),
                'telephone' => '250788000058',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manufacturer 2',
                'email' => 'manufacturer2@gmail.com',
                'role_id' => Role::where('role', Role::MANUFACTURER_ROLE)->value('id'),
                'password' => Hash::make('manufacturer12345'),
                'telephone' => '250788000051',
                'created_at' => now(),
                'updated_at' => now()
            ],
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
            if ($user->role->role == Role::STOCK_MANAGER_ROLE) {
                $newStockManager = [
                    'id' => Str::uuid()->toString(),
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                StockManager::insert($newStockManager);
            }
            if ($user->role->role == Role::MANUFACTURER_ROLE) {
                $newManufacturer = [
                    'id' => Str::uuid()->toString(),
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                Manufacturer::insert($newManufacturer);
            }
        }

        // assign some seeded districts to managers
        $allManagers = \App\Models\Manager::get();
        $latestDistricts = \App\Models\District::latest()->take($allManagers->count())->orderBy('district', 'asc')->get();
        for ($i=0; $i < $latestDistricts->count() ; $i++) { 
            for ($j = 0; $j < $allManagers->count(); $j++) {
                if ($i == $j) {
                    $latestDistricts[$i]->update([
                        'manager_id' => $allManagers[$i]->id
                    ]);
                }
            }
        }
    }
}
