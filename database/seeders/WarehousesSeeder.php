<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('warehouses')->delete();
        $districts = District::get();
        foreach ($districts as $district) {
            $district->warehouses()->create(
                [
                    'id' => Str::uuid()->toString(),
                    'name' => '' . $district->district . '-WAREHOUSE',
                    'created_at' => now(),
                    'updated_at' => now()
                ],

            );
        }
    }
}
