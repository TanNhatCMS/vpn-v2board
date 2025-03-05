<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Utils\Helper;
use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\DB;

class V2boardInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'v2board:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'v2board install';

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
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->info('__     ______  ____                      _  ');
            $this->info("\ \   / /___ \| __ )  ___   __ _ _ __ __| | ");
            $this->info(" \ \ / /  __) |  _ \ / _ \ / _` | '__/ _` | ");
            $this->info("  \ V /  / __/| |_) | (_) | (_| | | | (_| | ");
            $this->info("   \_/  |_____|____/ \___/ \__,_|_|  \__,_| ");
            if (\File::exists(base_path().'/.env')) {
                $securePath = config('v2board.secure_path', config('v2board.frontend_admin_path', hash('crc32b', config('app.key'))));
                $this->info("访问 http(s)://你的站点/{$securePath} 进入管理面板，你可以在用户中心修改你的密码。");
                abort(500, '如需重新安装请删除目录下.env文件');
            }

            if (! copy(base_path().'/.env.example', base_path().'/.env')) {
                abort(500, '复制环境文件失败，请检查目录权限');
            }
            $this->saveToEnv([
                'APP_KEY' => 'base64:'.base64_encode(Encrypter::generateKey('AES-256-CBC')),
                'DB_HOST' => $this->ask('Please enter the database address (default:localhost）', 'localhost'),
                'DB_DATABASE' => $this->ask('Please enter the database name'),
                'DB_USERNAME' => $this->ask('Please enter the database username'),
                'DB_PASSWORD' => $this->ask('Please enter the database password'),
            ]);
            \Artisan::call('config:clear');
            \Artisan::call('config:cache');
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                abort(500, 'Database connection failed');
            }
            //            $file = \File::get(base_path() . '/database/install.sql');
            //            if (!$file) {
            //                abort(500, '数据库文件不存在');
            //            }
            //            $sql = str_replace("\n", "", $file);
            //            $sql = preg_split("/;/", $sql);
            //            if (!is_array($sql)) {
            //                abort(500, '数据库文件格式有误');
            //            }
            $this->info('Please wait for the database import...');
            //            foreach ($sql as $item) {
            //                try {
            //                    DB::select(DB::raw($item));
            //                } catch (\Exception $e) {
            //                }
            //            }
            $this->info('Database import completed');
            $email = '';
            while (! $email) {
                $email = $this->ask('Please enter the administrator email?');
            }
            $password = Helper::guid(false);
            if (! $this->registerAdmin($email, $password)) {
                abort(500, 'The administrator account registration failed, please try again');
            }

            $this->info('Everything is ready');
            $this->info("Administrator email:{$email}");
            $this->info("Administrator password:{$password}");

            $defaultSecurePath = hash('crc32b', config('app.key'));
            $this->info("访问 http(s)://你的站点/{$defaultSecurePath} 进入管理面板，你可以在用户中心修改你的密码。");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function registerAdmin($email, $password): bool
    {
        $user = new User;
        $user->email = $email;
        if (strlen($password) < 8) {
            abort(500, '管理员密码长度最小为8位字符');
        }
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->uuid = Helper::guid(true);
        $user->token = Helper::guid();
        $user->is_admin = 1;

        return $user->save();
    }

    private function saveToEnv($data = [])
    {
        function set_env_var($key, $value)
        {
            if (! is_bool(strpos($value, ' '))) {
                $value = '"'.$value.'"';
            }
            $key = strtoupper($key);

            $envPath = app()->environmentFilePath();
            $contents = file_get_contents($envPath);

            preg_match("/^{$key}=[^\r\n]*/m", $contents, $matches);

            $oldValue = count($matches) ? $matches[0] : '';

            if ($oldValue) {
                $contents = str_replace("{$oldValue}", "{$key}={$value}", $contents);
            } else {
                $contents = $contents."\n{$key}={$value}\n";
            }

            $file = fopen($envPath, 'w');
            fwrite($file, $contents);

            return fclose($file);
        }
        foreach ($data as $key => $value) {
            set_env_var($key, $value);
        }

        return true;
    }
}
