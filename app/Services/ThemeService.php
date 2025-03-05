<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ThemeService
{
    private string $path;

    private $theme;

    public function __construct($theme)
    {
        $this->theme = $theme;
        $this->path = $path = public_path('theme/');
    }

    public function init(): void
    {
        $themeConfigFile = $this->path."{$this->theme}/config.json";
        if (! File::exists($themeConfigFile)) {
            abort(500, "{$this->theme} chủ đề không tồn tại");
        }
        $themeConfig = json_decode(File::get($themeConfigFile), true);
        if (! isset($themeConfig['configs']) || ! is_array($themeConfig)) {
            abort(500, "{$this->theme} the theme configuration file is incorrect");
        }
        $configs = $themeConfig['configs'];
        $data = [];
        foreach ($configs as $config) {
            $data[$config['field_name']] = $config['default_value'] ?? '';
        }

        $data = var_export($data, 1);
        try {
            if (! File::put(base_path()."/config/theme/{$this->theme}.php", "<?php\n return $data ;")) {
                abort(500, "{$this->theme} initialization failed");
            }
        } catch (\Exception $e) {
            abort(500, 'please check the v2board directory permissions');
        }

        try {
            Artisan::call('config:cache');
            while (true) {
                if (config("theme.{$this->theme}")) {
                    break;
                }
            }
        } catch (\Exception $e) {
            abort(500, "{$this->theme} initialization failed");
        }
    }
}
