<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Leader;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaderDistrictLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allLeaders = Leader::whereNull('district_id')->get();
        $districts = District::whereNotNull('manager_id')->get();

        if (count($allLeaders) > 0 && count($districts) > 0) {
            for ($i = 0; $i < $districts->count(); $i++) {
                for ($j = 0; $j < $allLeaders->count(); $j++) {
                    if ($i == $j) {
                        $allLeaders[$i]->update([
                            'district_id' => $districts[$i]->id
                        ]);
                    }
                }
            }
        }
    }
}
