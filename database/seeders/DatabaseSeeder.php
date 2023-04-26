<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(rolesSeeder::class);
        $this->call(usersSeeder::class);
        $this->call(permissionsSeeder::class);
        $this->call(userPermissionsSeeder::class);
        $this->call(provincesSeeder::class);
        $this->call(districtsSeeder::class);
        $this->call(stockModelsSeeder::class);
        $this->call(PaymentPlansSeeder::class);
        $this->call(ManagerDistrictLinkSeeder::class);
        $this->call(MainWarehousesSeeder::class);
        $this->call(WarehousesSeeder::class);
    }
}
