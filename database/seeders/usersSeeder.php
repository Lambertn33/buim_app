<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
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
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'district manager',
                'email' => 'manager@gmail.com',
                'password' => Hash::make('manager12345'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'sector leader',
                'email' => 'leader@gmail.com',
                'password' => Hash::make('leader12345'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        User::insert($users);
    }
}
