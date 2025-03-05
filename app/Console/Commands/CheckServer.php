<?php

namespace App\Console\Commands;

use App\Services\ServerService;
use App\Services\TelegramService;
use App\Utils\CacheKey;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nhiệm vụ kiểm tra Node';

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
        $this->checkOffline();
    }

    private function checkOffline(): void
    {
        $serverService = new ServerService;
        $servers = $serverService->getAllServers();
        foreach ($servers as $server) {
            if ($server['parent_id']) {
                continue;
            }
            if ($server['last_check_at'] && (time() - $server['last_check_at']) > 1800) {
                $telegramService = new TelegramService;
                $message = sprintf(
                    "Thông báo Node ngắt kết nối  \r\n----\r\nTên Node:%s\r\nĐịa chỉ Node:%s\r\n",
                    $server['name'],
                    $server['host']
                );
                $telegramService->sendMessageWithAdmin($message);
                Cache::forget(CacheKey::get(sprintf('SERVER_%s_LAST_CHECK_AT', strtoupper($server['type'])), $server->id));
            }
        }
    }
}
