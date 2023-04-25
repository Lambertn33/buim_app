<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagerDistrictLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // assign some seeded districts to managers
        $allManagers = \App\Models\Manager::get();
        $latestDistricts = \App\Models\District::latest()->take($allManagers->count())->orderBy('district', 'asc')->get();
        if (count($allManagers) > 0 && count($latestDistricts) > 0) {
            for ($i = 0; $i < $latestDistricts->count(); $i++) {
                for ($j = 0; $j < $allManagers->count(); $j++) {
                    if ($i == $j && $latestDistricts[$i]->manager_id === null) {
                        $latestDistricts[$i]->update([
                            'manager_id' => $allManagers[$i]->id
                        ]);
                    }
                }
            }
        }
    }
}
