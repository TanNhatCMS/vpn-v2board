<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrafficFetchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $u;

    protected $d;

    protected $userId;

    protected array $server;

    protected $protocol;

    public int $tries = 3;

    public int $timeout = 10;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($u, $d, $userId, array $server, $protocol)
    {
        $this->onQueue('traffic_fetch');
        $this->u = $u;
        $this->d = $d;
        $this->userId = $userId;
        $this->server = $server;
        $this->protocol = $protocol;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $user = User::lockForUpdate()->find($this->userId);
        if (! $user) {
            return;
        }

        $user->t = time();
        $user->u = $user->u + ($this->u * $this->server['rate']);
        $user->d = $user->d + ($this->d * $this->server['rate']);
        if (! $user->save()) {
            info("Cập nhật lưu lượng truy cập không thành công\nID người dùng không được ghi lại:{$this->userId}\nđường lên không được ghi lại:{$user->u}\nKhông có liên kết được ghi lại:{$user->d}");
        }
    }
}
