<?php

namespace Database\Seeders;

use App\Models\Resident;
use App\Models\SocialAssistance;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialAssistanceRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $social_assistances = SocialAssistance::all();
        $recipients = Resident::all();

        $filteredRecords = $recipients->filter(function ($resident) {
            $result = $resident->calculateEligibilityStatus() ? 'eligible' : 'not_eligible';
            return $result === 'eligible';
        });

        foreach ($social_assistances as $social_assistance) {
            $data = $filteredRecords->shuffle()->take(100)->toArray();
            foreach ($data as $recipient) {
                SocialAssistanceRecipient::create([
                    'social_assistance_id' => $social_assistance->id,
                    'resident_id' => $recipient['id'],
                ]);
            }
        }
    }
}
