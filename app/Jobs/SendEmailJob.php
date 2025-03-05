<?php

namespace App\Jobs;

use App\Models\MailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $params;

    public int $tries = 3;

    public int $timeout = 10;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params, $queue = 'send_email')
    {
        $this->onQueue($queue);
        $this->params = $params;
    }

    /**
     * Execute the job.
     */
    public function handle(): array
    {
        if (config('v2board.email_host')) {
            Config::set('mail.host', config('v2board.email_host', config('mail.mailers.smtp.host')));
            Config::set('mail.port', config('v2board.email_port', config('mail.mailers.smtp.port')));
            Config::set('mail.encryption', config('v2board.email_encryption', 'tls'));
            Config::set('mail.username', config('v2board.email_username', config('mail.mailers.smtp.username')));
            Config::set('mail.password', config('v2board.email_password', config('mail.mailers.smtp.password')));
            Config::set('mail.from.address', config('v2board.email_from_address', config('mail.from.address')));
            Config::set('mail.from.name', config('v2board.app_name', 'V2Board'));
        }
        $params = $this->params;
        $email = $params['email'];
        $subject = $params['subject'];
        $params['template_name'] = 'mail.'.config('v2board.email_template', 'default').'.'.$params['template_name'];
        try {
            Mail::send(
                $params['template_name'],
                $params['template_value'],
                function ($message) use ($email, $subject) {
                    $message->to($email)->subject($subject);
                }
            );
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $log = [
            'email' => $params['email'],
            'subject' => $params['subject'],
            'template_name' => $params['template_name'],
            'error' => $error ?? null,
        ];

        MailLog::create($log);
        $log['config'] = config('mail');

        return $log;
    }
}
