<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PartnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('screening_partners')->delete();

        DB::table('screening_partners')->insert(array(
            0 => array (
                'id' => Str::uuid()->toString(),
                'name' => 'RDB',
                'description' => 'Rwanda Development Board',
                'created_at' => now(),
                'updated_at' => now()
            ),
            1 => array (
                'id' => Str::uuid()->toString(),
                'name' => 'MTN',
                'description' => 'Mobile Telephone Network',
                'created_at' => now(),
                'updated_at' => now()
            ),            
        ));
    }
}
