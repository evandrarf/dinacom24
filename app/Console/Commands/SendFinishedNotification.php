<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Report;
use Illuminate\Console\Command;

class SendFinishedNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send-finished-notification {social_assistance_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send finished social assistance notification to user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $social_assistance_id = $this->argument('social_assistance_id');

        $reportIds = Report::where('social_assistance_id', $social_assistance_id)->get()->pluck('id')->toArray();

        $existingNotification = Notification::where('type', 'finished')->where('data_type', 'report')->whereIn('data_id', $reportIds)->get()->pluck('data_id')->toArray();

        $reportIds = array_diff($reportIds, $existingNotification);
        $reports = Report::with('ticket')->whereIn('id', $reportIds)->get();

        $data = [];

        foreach ($reports as $report) {
            $data[] = [
                'title' => 'Bantuan Sosial Sudah Selesai',
                'body' => 'Bantuan sosial sudah terlaksana, silahkan lihat laporan dari bantuan sosial anda',
                'data_id' => $report->id,
                'data_type' => 'report',
                'type' => 'finished',
                'resident_id' => $report->resident_id,
                'social_assistance_id' => $report->social_assistance_id,
                'social_assistance_recipient_id' => $report->ticket->social_assistance_recipient_id,
                'created_time' => now()->timestamp,
            ];
        }

        Notification::insert($data);
    }
}
