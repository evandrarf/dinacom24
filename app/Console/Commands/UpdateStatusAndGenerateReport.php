<?php

namespace App\Console\Commands;

use App\Models\SocialAssistance;
use Illuminate\Console\Command;

class UpdateStatusAndGenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'social-assistance:update-status-and-generate-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status and generate report for social assistance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $socialAssistances = SocialAssistance::where('end_date', '<', now())->get();

        foreach ($socialAssistances as $socialAssistance) {
            $socialAssistance->update(['status' => 'finished']);

            $this->call('social-assistance:generate-report', [
                'social_assistance_id' => $socialAssistance->id,
            ]);
            $this->call('notification:send-finished-notification', [
                'social_assistance_id' => $socialAssistance->id,
            ]);
        }
    }
}
