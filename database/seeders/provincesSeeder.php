<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use Illuminate\Support\Str;

class provincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provinces')->delete();

        foreach(Province::PROVINCES as $province) {
            $province = [
                'id' => Str::uuid()->toString(),
                'province' => $province,
                'created_at' => now(),
                'updated_at' => now()
            ];
            Province::insert($province);
        }
    }
}
