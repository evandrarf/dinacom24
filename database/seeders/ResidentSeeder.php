<?php

namespace Database\Seeders;

use App\Models\Resident;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $village = Village::all()->pluck('id')->toArray();

        Resident::create([
            'head_of_family_name' => 'Evandra Raditya',
            'head_of_family_nik' => '3374132707060001',
            'family_card_number' => '3374132302230007',
            'address' => 'JL. Aribuana No. 1',
            'rt' => '001',
            'rw' => '011',
            'village_id' => Village::where('name', 'Krobokan')->first()->id,
            'district' => 'Semarang Barat',
            'city' => 'Semarang',
            'province' => 'Jawa Tengah',
            'postal_code' => '50141',
            'phone_number' => '081226747723',
            'monthly_income' => 100000,
            'dependent_count' => 3,
            'house_ownership' => 'owned',
            'house_type' => 'permanent',
        ]);

        foreach ($village as $villageId) {
            \App\Models\Resident::factory()->count(10)->create([
                'village_id' => $villageId,
            ]);
        }
    }
}
