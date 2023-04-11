<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

class districtsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('districts')->delete();
        $districts = [
            // KIGALI
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::KIGALI)->value('id'),
                'district' => 'KICUKIRO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::KIGALI)->value('id'),
                'district' => 'GASABO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::KIGALI)->value('id'),
                'district' => 'NYARUGENGE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            //NORTHERN
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::NORTHERN)->value('id'),
                'district' => 'BURERA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::NORTHERN)->value('id'),
                'district' => 'GAKENKE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::NORTHERN)->value('id'),
                'district' => 'GICUMBI',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::NORTHERN)->value('id'),
                'district' => 'MUSANZE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::NORTHERN)->value('id'),
                'district' => 'RULINDO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // SOUTHERN
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::SOUTHERN)->value('id'),
                'district' => 'GISAGARA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::SOUTHERN)->value('id'),
                'district' => 'HUYE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::SOUTHERN)->value('id'),
                'district' => 'KAMONYI',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::SOUTHERN)->value('id'),
                'district' => 'MUHANGA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::SOUTHERN)->value('id'),
                'district' => 'NYAMAGABE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::SOUTHERN)->value('id'),
                'district' => 'NYANZA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::SOUTHERN)->value('id'),
                'district' => 'NYARUGURU',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::SOUTHERN)->value('id'),
                'district' => 'RUHANGO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // EASTERN
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::EASTERN)->value('id'),
                'district' => 'BUGESERA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::EASTERN)->value('id'),
                'district' => 'GATSIBO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::EASTERN)->value('id'),
                'district' => 'KAYONZA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::EASTERN)->value('id'),
                'district' => 'KIREHE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::EASTERN)->value('id'),
                'district' => 'NGOMA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::EASTERN)->value('id'),
                'district' => 'NYAGATARE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::EASTERN)->value('id'),
                'district' => 'RWAMAGANA',
                'created_at' => now(),
                'updated_at' => now()
            ],

            //WESTERN
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::WESTERN)->value('id'),
                'district' => 'KARONGI',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::WESTERN)->value('id'),
                'district' => 'NGORORERO',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::WESTERN)->value('id'),
                'district' => 'NYABIHU',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::WESTERN)->value('id'),
                'district' => 'NYAMASHEKE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::WESTERN)->value('id'),
                'district' => 'RUBAVU',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::WESTERN)->value('id'),
                'district' => 'RUSIZI',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid()->toString(),
                'province_id' => Province::where('province', Province::WESTERN)->value('id'),
                'district' => 'RUTSIRO',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        District::insert($districts);
    }
}
