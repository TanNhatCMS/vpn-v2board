<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Utils\Helper;
use Illuminate\Console\Command;

class ResetUser extends Command
{
    protected $builder;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đặt lại tất cả thông tin người dùng';

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
        if (! $this->confirm('Are you sure you want to reset all user security information?')) {
            return;
        }
        ini_set('memory_limit', -1);
        $users = User::all();
        foreach ($users as $user) {
            $user->token = Helper::guid();
            $user->uuid = Helper::guid(true);
            $user->save();
            $this->info("Đặt lại người dùng {$user->email} thông tin bảo mật");
        }
    }
}
