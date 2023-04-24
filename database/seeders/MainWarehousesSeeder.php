<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MainWarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('main_warehouses')->delete();

        DB::table('main_warehouses')->insert(array(
            0 => array(
                'id' => Str::uuid()->toString(),
                'name' => 'DP World warehouse',
                'description' => 'Main Warehouse that stores cargo from abroad / from manufacturer',
                'location' => 'KIGALI',
                'created_at' => now(),
                'updated_at' => now()
            ),
            1 => array(
                'id' => Str::uuid()->toString(),
                'name' => 'HQ warehouse',
                'description' => 'A small warehouse for Devices located at the head offices (Repair, Testing, Exibition, etc…)',
                'location' => 'KIGALI',
                'created_at' => now(),
                'updated_at' => now()
            ),
            2 => array(
                'id' => Str::uuid()->toString(),
                'name' => 'Rugando warehouse',
                'description' => 'A transitory warehouse in Rugando - Kimihurura, for stock ready to be transferred into districts',
                'location' => 'KIGALI',
                'created_at' => now(),
                'updated_at' => now()
            ),
        ));
    }
}
