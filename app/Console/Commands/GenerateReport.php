<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Models\SocialAssistance;
use App\Models\Ticket;
use Illuminate\Console\Command;

class GenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'social-assistance:generate-report {social_assistance_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate report for social assistance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $social_assistance_id = $this->argument('social_assistance_id');
        $socialAssistance = SocialAssistance::with('recipients')->find($social_assistance_id);
        $socialAssistanceRecipients = $socialAssistance->recipients->pluck('resident_id')->toArray();

        $existingReport = Report::where('social_assistance_id', $social_assistance_id)->get()->pluck('resident_id')->toArray();

        $residentIds = array_diff($socialAssistanceRecipients, $existingReport);
        $tickets = Ticket::where('social_assistance_id', $social_assistance_id)->whereIn('resident_id', $residentIds)->get();

        $data = [];

        foreach ($tickets as $ticket) {
            $data[] = [
                'ticket_id' => $ticket->id,
                'resident_id' => $ticket->resident_id,
                'social_assistance_id' => $ticket->social_assistance_id,
                'taken_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        Report::insert($data);
    }
}
