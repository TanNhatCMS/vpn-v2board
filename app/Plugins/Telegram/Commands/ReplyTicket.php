<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;
use App\Services\TicketService;

class ReplyTicket extends Telegram
{
    public string $regex = '/[#](.*)/';

    public string $description = 'Trả lời đơn đặt hàng công việc nhanh chóng';

    public function handle($message, $match = []): void
    {
        if (! $message->is_private) {
            return;
        }
        $this->replayTicket($message, $match[1]);
    }

    private function replayTicket($msg, $ticketId): void
    {
        $user = User::where('telegram_id', $msg->chat_id)->first();
        if (! $user) {
            abort(500, 'Người dùng không tồn tại');
        }
        if (! $msg->text) {
            return;
        }
        if (! ($user->is_admin || $user->is_staff)) {
            return;
        }
        $ticketService = new TicketService;
        $ticketService->replyByAdmin(
            $ticketId,
            $msg->text,
            $user->id
        );
        $telegramService = $this->telegramService;
        $telegramService->sendMessage($msg->chat_id, "#`{$ticketId}` Lệnh làm việc đã được trả lời thành công", 'markdown');
        $telegramService->sendMessageWithAdmin("#`{$ticketId}`Lệnh làm việc đã được {$user->email} trả lời", true);
    }
}
