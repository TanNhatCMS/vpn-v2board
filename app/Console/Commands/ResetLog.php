<?php

namespace App\Console\Commands;

use App\Models\Log;
use App\Models\StatServer;
use App\Models\StatUser;
use Illuminate\Console\Command;

class ResetLog extends Command
{
    protected $builder;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xóa nhật ký';

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
     */
    public function handle(): void
    {
        StatUser::where('record_at', '<', strtotime('-2 month', time()))->delete();
        StatServer::where('record_at', '<', strtotime('-2 month', time()))->delete();
        Log::where('created_at', '<', strtotime('-1 month', time()))->delete();
    }
}
