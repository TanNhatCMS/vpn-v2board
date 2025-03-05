<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MailService;
use Illuminate\Console\Command;

class SendRemindMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:remindMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi email nhắc nhở';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $users = User::all();
        $mailService = new MailService;
        foreach ($users as $user) {
            if ($user->remind_expire) {
                $mailService->remindExpire($user);
            }
            if ($user->remind_traffic) {
                $mailService->remindTraffic($user);
            }
        }
    }
}
