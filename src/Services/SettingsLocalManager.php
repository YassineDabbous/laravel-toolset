<?php
namespace Ysn\SuperCore\Services;

use Ysn\SuperCore\Contracts\SettingsManager;

class SettingsLocalManager implements SettingsManager
{
    public function update(array $updates, string $key = 'settings.config') : array{
        // merge updates with in-memory configs
        $merged = array_merge(config($key), $updates);

        // save to memory
        config()->set($key, $merged);

        // get full configs by main key 
        $name = explode('.', $key)[0];
        $full = config($name);
        // \Log::alert($full);

        // array to php code
        $configs = var_export($full, true);

        // formatting
        $configs = str_replace(" \n", ' ', $configs);
        $configs = str_replace('  ', ' ', $configs);

        // writing
        $content = <<<EOT
        <?php
        return $configs;
        EOT;
        file_put_contents(config_path($name.'.php'), $content);
        
        return $merged;
    }
}
