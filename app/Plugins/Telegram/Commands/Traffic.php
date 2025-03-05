<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;
use App\Utils\Helper;

class Traffic extends Telegram
{
    public string $command = '/traffic';

    public string $description = 'Query traffic information';

    public function handle($message, $match = []): void
    {
        $telegramService = $this->telegramService;
        if (! $message->is_private) {
            return;
        }
        $user = User::where('telegram_id', $message->chat_id)->first();
        if (! $user) {
            $telegramService->sendMessage($message->chat_id, 'Thông tin người dùng của bạn chưa được tìm thấy, vui lòng liên kết tài khoản của bạn trước', 'markdown');

            return;
        }
        $transferEnable = Helper::trafficConvert($user->transfer_enable);
        $up = Helper::trafficConvert($user->u);
        $down = Helper::trafficConvert($user->d);
        $remaining = Helper::trafficConvert($user->transfer_enable - ($user->u + $user->d));
        $text = "🚥Truy vấn lưu lượng\n———————————————\nLưu lượng truy cập theo kế hoạch:`{$transferEnable}`\nĐã sử dụng lên: `{$up}`\nĐã sử dụng xuống:`{$down}`\nLưu lượng còn lại:`{$remaining}`";
        $telegramService->sendMessage($message->chat_id, $text, 'markdown');
    }
}
