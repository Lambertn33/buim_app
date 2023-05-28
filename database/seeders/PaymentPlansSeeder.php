<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_plans')->delete();

        DB::table('payment_plans')->insert(array(
            0 => array(
                'id' => Str::uuid()->toString(),
                'title' => 'Category 1 (10%)',
                'percentage' => 10,
                'duration' => 90,
                'created_at' => now(),
                'updated_at' => now()
            ),
            1 => array(
                'id' => Str::uuid()->toString(),
                'title' => 'Category 1 (20%)',
                'percentage' => 20,
                'duration' => 180,
                'created_at' => now(),
                'updated_at' => now()
            ),
            2 => array(
                'id' => Str::uuid()->toString(),
                'title' => 'Category 1 (30%)',
                'percentage' => 30,
                'duration' => 360,
                'created_at' => now(),
                'updated_at' => now()
            ),
        ));
    }
}
