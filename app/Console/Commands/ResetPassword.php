<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Utils\Helper;
use Illuminate\Console\Command;

class ResetPassword extends Command
{
    protected $builder;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:password {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đặt lại mật khẩu người dùng';

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
     */
    public function handle(): void
    {
        $user = User::where('email', $this->argument('email'))->first();
        if (! $user) {
            abort(500, 'Địa chỉ email không tồn tại ');
        }
        $password = Helper::guid(false);
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->password_algo = null;
        if (! $user->save()) {
            abort(500, 'Đặt lại không thành công');
        }
        $this->info('!!!Đặt lại thành công!!!');
        $this->info("Mật khẩu mới là: {$password}, Vui lòng thay đổi mật khẩu của bạn càng sớm càng tốt.");
    }
}
