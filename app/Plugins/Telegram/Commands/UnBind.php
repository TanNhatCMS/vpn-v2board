<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;

class UnBind extends Telegram
{
    public string $command = '/unbind';

    public string $description = 'Unbind Telegram tài khoản từ trang web';

    public function handle($message, $match = []): void
    {
        if (! $message->is_private) {
            return;
        }
        $user = User::where('telegram_id', $message->chat_id)->first();
        $telegramService = $this->telegramService;
        if (! $user) {
            $telegramService->sendMessage($message->chat_id, 'Thông tin người dùng của bạn chưa được tìm thấy, vui lòng liên kết tài khoản của bạn trước', 'markdown');

            return;
        }
        $user->telegram_id = null;
        if (! $user->save()) {
            abort(500, 'Hủy bỏ liên kết thất bại');
        }
        $telegramService->sendMessage($message->chat_id, 'Hủy bỏ liên kết thành công', 'markdown');
    }
}
