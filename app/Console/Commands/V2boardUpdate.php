<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;

class V2boardUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'v2board:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'v2board update';

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
        $this->info('Bắt đầu cập nhật...');
        $this->info('Xóa bộ nhớ cache, vui lòng đợi...');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:clear');
        $this->info('Cập nhật cơ sở dữ liệu, vui lòng đợi...');
        Artisan::call('migrate');
        $this->info('Cập nhật dữ liệu mẫu, vui lòng đợi...');
        Artisan::call('horizon:terminate');
        $this->info('Sau khi cập nhật hoàn tất, dịch vụ hàng đợi đã được khởi động lại và bạn không cần phải làm gì cả.');
    }
}
