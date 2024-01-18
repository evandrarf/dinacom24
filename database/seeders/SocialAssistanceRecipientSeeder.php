<?php

namespace Database\Seeders;

use App\Actions\Utility\GenerateQrCode;
use App\Models\Resident;
use App\Models\SocialAssistance;
use App\Models\SocialAssistanceRecipient;
use App\Models\Ticket;
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

        $generateQrCode = new GenerateQrCode();

        foreach ($social_assistances as $social_assistance) {
            $data = $filteredRecords->shuffle()->take(100)->toArray();
            foreach ($data as $recipient) {
                $ticketNumber = time() . rand(1000000, 9999999);
                $social_assistance_recipient = SocialAssistanceRecipient::create([
                    'social_assistance_id' => $social_assistance->id,
                    'resident_id' => $recipient['id'],
                ]);
                $qrCode = $generateQrCode->handle($ticketNumber);
                Ticket::create([
                    'social_assistance_id' => $social_assistance->id,
                    'resident_id' => $recipient['id'],
                    'ticket_number' => $ticketNumber,
                    'qr_code_file_id' => $qrCode->id,
                    'social_assistance_recipient_id' => $social_assistance_recipient->id,
                ]);
            }
        }
    }
}
