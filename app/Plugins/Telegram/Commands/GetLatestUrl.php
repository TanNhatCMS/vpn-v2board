<?php

namespace App\Plugins\Telegram\Commands;

use App\Plugins\Telegram\Telegram;

class GetLatestUrl extends Telegram
{
    public string $command = '/getlatesturl';

    public string $description = 'Liên kết tài khoản Telegram với trang web';

    public function handle($message, $match = []): void
    {
        $telegramService = $this->telegramService;
        $text = sprintf(
            '%sĐịa chỉ trang web mới nhất là:%s',
            config('v2board.app_name', 'V2Board'),
            config('v2board.app_url')
        );
        $telegramService->sendMessage($message->chat_id, $text, 'markdown');
    }
}
