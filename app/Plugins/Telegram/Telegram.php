<?php

namespace App\Plugins\Telegram;

use App\Services\TelegramService;

abstract class Telegram
{
    abstract protected function handle($message, $match);

    public TelegramService $telegramService;

    public function __construct()
    {
        $this->telegramService = new TelegramService;
    }
}
