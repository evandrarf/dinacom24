<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\SocialAssistance;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Console\Command;

class SendReminderNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send-reminder-start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder start of social assistance notification to user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all recipient of social assistance
        $socialAssistances = SocialAssistance::where('start_date', "<=", now()->format("Y-m-d"))->where('status', 'active')->with('recipients')->get();

        $recipients = [];
        foreach ($socialAssistances as $socialAssistance) {
            foreach ($socialAssistance->recipients as $recipient) {
                $recipients[] = [
                    'social_assistance_recipient_id' => $recipient->id,
                    'resident_id' => $recipient->resident_id,
                ];
            }
        }

        $existing = Notification::where('type', 'reminder_start')->select('social_assistance_recipient_id', 'resident_id')->get()->toArray();

        $recipients = array_filter($recipients, function ($item) use ($existing) {
            return !in_array($item, $existing);
        });

        $social_assistance_recipient_ids = collect($recipients)->pluck('social_assistance_recipient_id')->toArray();

        $recipients = SocialAssistanceRecipient::whereIn('id', $social_assistance_recipient_ids)->with('ticket', 'resident')->get();

        $data = [];

        foreach ($recipients as $recipient) {
            $data[] = [
                'title' => 'Bantuan Sosial Sudah Bisa Diambil',
                'body' => 'Bantuan sosial sudah bisa diambil di kantor desa/kelurahan ' . $recipient->resident->village->name,
                'type' => 'reminder_start',
                'data_id' => $recipient->ticket->id,
                'data_type' => 'ticket',
                'resident_id' => $recipient->resident_id,
                'social_assistance_recipient_id' => $recipient->id,
                'social_assistance_id' => $recipient->social_assistance_id,
            ];
        }

        Notification::insert($data);

        $this->info('Notification reminder start of social assistance has been sent.');
    }
}
