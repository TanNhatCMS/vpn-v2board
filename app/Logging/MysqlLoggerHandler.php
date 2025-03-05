<?php

namespace App\Logging;

use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class MysqlLoggerHandler extends AbstractProcessingHandler
{
    public function __construct($level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array|LogRecord $record): void
    {
        try {
            if (isset($record['context']['exception']) && is_object($record['context']['exception'])) {
                $record['context']['exception'] = (array) $record['context']['exception'];
            }
            $record['request_data'] = request()->all() ?? [];
            $log = [
                'title' => $record['message'],
                'level' => $record['level_name'],
                'host' => $record['request_host'] ?? request()->getSchemeAndHttpHost(),
                'uri' => $record['request_uri'] ?? request()->getRequestUri(),
                'method' => $record['request_method'] ?? request()->getMethod(),
                'ip' => request()->getClientIp(),
                'data' => json_encode($record['request_data']),
                'context' => isset($record['context']) ? json_encode($record['context']) : '',
                'created_at' => strtotime($record['datetime']),
                'updated_at' => strtotime($record['datetime']),
            ];

            LogModel::insert(
                $log
            );
        } catch (\Exception $e) {
            Log::channel('daily')->error($e->getMessage().$e->getFile().$e->getTraceAsString());
        }
    }
}
