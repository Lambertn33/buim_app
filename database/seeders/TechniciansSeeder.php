<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\District;
use App\Models\Technician;
use Illuminate\Support\Str;

class TechniciansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('technicians')->delete();
        $technicians = [];
        foreach (District::get() as $district) {
            $randomNumber = rand(1000000, 9999999);
            $technicians[] = [
                [
                    'id' => Str::uuid()->toString(),
                    'district_id' => $district->id,
                    'names' => 'Technician 1 for ' . $district->district . '',
                    'telephone' => '25078' . $randomNumber . ''
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'district_id' => $district->id,
                    'names' => 'Technician 2 for ' . $district->district . '',
                    'telephone' => '25078' . $randomNumber . ''
                ]
            ];
        }
        foreach ($technicians as $technician) {
            Technician::insert($technician);
        }
    }
}
