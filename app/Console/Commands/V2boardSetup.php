<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Utils\Helper;
use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class V2boardSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'v2board:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'v2board setup';

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
     */
    public function handle(): void
    {
        try {
            $this->info('__     ______  ____                      _  ');
            $this->info("\ \   / /___ \| __ )  ___   __ _ _ __ __| | ");
            $this->info(" \ \ / /  __) |  _ \ / _ \ / _` | '__/ _` | ");
            $this->info("  \ V /  / __/| |_) | (_) | (_| | | | (_| | ");
            $this->info("   \_/  |_____|____/ \___/ \__,_|_|  \__,_| ");
            Artisan::call('config:clear');
            Artisan::call('config:cache');
            $email = '';
            while (! $email) {
                $email = $this->ask('Vui lòng nhập email quản trị viên?');
            }
            $name = '';
            while (! $name) {
                $name = $this->ask('Vui lòng nhập tên quản trị viên?');
            }
            $password = Helper::guid();
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                abort(500, 'Kết nối cơ sở dữ liệu không thành công');
            }
            if (! $this->registerAdmin($email, $name, $password)) {
                abort(500, 'Đăng ký tài khoản quản trị viên không thành công, vui lòng thử lại');
            }

            $this->info('Mọi thứ đã sẵn sàng');
            $this->info("Email quản trị viên:{$email}");
            $this->info("Mật khẩu quản trị viên:{$password}");

            $defaultSecurePath = hash('crc32b', config('app.key'));
            $this->info("Truy cập http(s)://Trang web của bạn/{$defaultSecurePath} Truy cập bảng quản trị và bạn có thể thay đổi mật khẩu của mình trong trung tâm người dùng.");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function registerAdmin($email, $name, $password): bool
    {
        $user = new User;
        $user->email = $email;
        if (strlen($password) < 8) {
            abort(500, 'Độ dài tối thiểu của mật khẩu quản trị viên là 8 ký tự');
        }
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->name = $name;
        $user->uuid = Helper::guid(true);
        $user->token = Helper::guid();
        $user->is_admin = 1;

        return $user->save();
    }
}
