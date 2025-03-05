<?php

namespace App\Console\Commands;

use App\Models\Stat;
use App\Models\StatServer;
use App\Models\StatUser;
use App\Services\StatisticalService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class V2boardStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'v2board:statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Statistical tasks';

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
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $startAt = microtime(true);
        ini_set('memory_limit', -1);
        $this->statUser();
        $this->statServer();
        $this->stat();
        $this->info('Tiêu thụ'.(microtime(true) - $startAt));
    }

    /**
     * @throws Exception
     */
    private function statServer(): void
    {
        $createdAt = time();
        $recordAt = strtotime('-1 day', strtotime(date('Y-m-d')));
        $statService = new StatisticalService;
        $statService->setStartAt($recordAt);
        $statService->setServerStats();
        $stats = $statService->getStatServer();
        DB::beginTransaction();
        foreach ($stats as $stat) {
            if (! StatServer::insert([
                'server_id' => $stat['server_id'],
                'server_type' => $stat['server_type'],
                'u' => $stat['u'],
                'd' => $stat['d'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'record_type' => 'd',
                'record_at' => $recordAt,
            ])) {
                DB::rollback();
                throw new Exception('máy chủ stat không thành công');
            }
        }
        DB::commit();
        $statService->clearStatServer();
    }

    /**
     * @throws Exception
     */
    private function statUser(): void
    {
        $createdAt = time();
        $recordAt = strtotime('-1 day', strtotime(date('Y-m-d')));
        $statService = new StatisticalService;
        $statService->setStartAt($recordAt);
        $statService->setUserStats();
        $stats = $statService->getStatUser();
        DB::beginTransaction();
        foreach ($stats as $stat) {
            if (! StatUser::insert([
                'user_id' => $stat['user_id'],
                'u' => $stat['u'],
                'd' => $stat['d'],
                'server_rate' => $stat['server_rate'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'record_type' => 'd',
                'record_at' => $recordAt,
            ])) {
                DB::rollback();
                throw new Exception('người dùng stat không thành công');
            }
        }
        DB::commit();
        $statService->clearStatUser();
    }

    private function stat(): void
    {
        $endAt = strtotime(date('Y-m-d'));
        $startAt = strtotime('-1 day', $endAt);
        $statisticalService = new StatisticalService;
        $statisticalService->setStartAt($startAt);
        $statisticalService->setEndAt($endAt);
        $data = $statisticalService->generateStatData();
        $data['record_at'] = $startAt;
        $data['record_type'] = 'd';
        $statistic = Stat::where('record_at', $startAt)
            ->where('record_type', 'd')
            ->first();
        if ($statistic) {
            $statistic->update($data);

            return;
        }
        Stat::create($data);
    }
}
