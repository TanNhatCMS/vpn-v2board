<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ClearUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Làm sạch người dùng';

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
     * @return mixed
     */
    public function handle()
    {
        $builder = User::where('plan_id', null)
            ->where('transfer_enable', 0)
            ->where('expired_at', 0)
            ->where('last_login_at', null);
        $count = $builder->count();
        if ($builder->delete()) {
            $this->info("Đã xóa {$count} người dùng không có bất kỳ dữ liệu nào");
        }
    }
}
