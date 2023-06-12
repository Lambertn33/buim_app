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
        $this->call([
            rolesSeeder::class,
            usersSeeder::class,
            permissionsSeeder::class,
            userPermissionsSeeder::class,
            provincesSeeder::class,
            districtsSeeder::class,
            stockModelsSeeder::class,
            PaymentPlansSeeder::class,
            ManagerDistrictLinkSeeder::class,
            MainWarehousesSeeder::class,
            TechniciansSeeder::class,
            PartnersSeeder::class
        ]);
    }
}
