<?php

namespace App\Logging;

use Illuminate\Support\HigherOrderTapProxy;
use Monolog\Logger;

class MysqlLogger
{
    public function __invoke(array $config): Logger|HigherOrderTapProxy
    {
        return tap(new Logger('mysql'), function ($logger) {
            $logger->pushHandler(new MysqlLoggerHandler);
        });
    }
}
