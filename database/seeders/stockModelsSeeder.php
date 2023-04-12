<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class stockModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stock_models')->delete();

        DB::table('stock_models')->insert(array(
            0 => array(
                'id' => Str::uuid()->toString(),
                'name' => 'Biolite',
                'created_at' => now(),
                'updated_at' => now()
            ),
            1 => array(
                'id' => Str::uuid()->toString(),
                'name' => 'Solar Run',
                'created_at' => now(),
                'updated_at' => now()
            ),
            2 => array(
                'id' => Str::uuid()->toString(),
                'name' => 'Yellow Box',
                'created_at' => now(),
                'updated_at' => now()
            ),
        ));
    }
}
