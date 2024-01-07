<?php

namespace Database\Seeders;

use App\Models\SocialAssistance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialAssistanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialAssistance::factory()->count(3)->create();
    }
}
