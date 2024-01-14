<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendFinishedNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send-finished-notification';

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
        //
    }
}
