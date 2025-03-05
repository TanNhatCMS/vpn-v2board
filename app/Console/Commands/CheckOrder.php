<?php

namespace App\Console\Commands;

use App\Jobs\OrderHandleJob;
use App\Models\Order;
use Illuminate\Console\Command;

class CheckOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nhiệm vụ kiểm tra đơn hàng';

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
        ini_set('memory_limit', -1);
        $orders = Order::whereIn('status', [0, 1])
            ->orderBy('created_at', 'ASC')
            ->get();
        foreach ($orders as $order) {
            OrderHandleJob::dispatch($order->trade_no);
        }
    }
}
