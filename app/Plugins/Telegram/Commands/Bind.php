<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;

class Bind extends Telegram
{
    public string $command = '/bind';

    public string $description = 'Liên kết tài khoản Telegram với trang web';

    public function handle($message, $match = []): void
    {
        if (! $message->is_private) {
            return;
        }
        if (! isset($message->args[0])) {
            abort(500, 'If the parameters are incorrect, please send it with the subscription address.');
        }
        $subscribeUrl = $message->args[0];
        $subscribeUrl = parse_url($subscribeUrl);
        parse_str($subscribeUrl['query'], $query);
        $token = $query['token'];
        if (! $token) {
            abort(500, 'Địa chỉ đăng ký không hợp lệ');
        }
        $user = User::where('token', $token)->first();
        if (! $user) {
            abort(500, 'Người dùng không tồn tại');
        }
        if ($user->telegram_id) {
            abort(500, 'Tài khoản đã bị ràng buộc với tài khoản Telegram');
        }
        $user->telegram_id = $message->chat_id;
        if (! $user->save()) {
            abort(500, 'Cài đặt không thành công');
        }
        $telegramService = $this->telegramService;
        $telegramService->sendMessage($message->chat_id, 'ràng buộc thành công');
    }
}
