<?php

namespace App\UserAuth\Support;

use Illuminate\Support\Arr;

class Config
{
    const DEFAULTS = 'defaults';
    const GUARDS = 'guards';

    static ?array $config = null;

    /**
     * Proxy access for configuration values of UserAuth scope
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|\Illuminate\Config\Repository
     */
    public static function config(string $key, $default = null)
    {
        return config('user-auth.' . ltrim($key, "."), $default);
    }
    
    /**
     * Prepare config for UserAuth 
     *
     * @return void
     */
    public static function prepareConfig()
    {
        $defaults = static::config(static::DEFAULTS, []);
        $defaultsAsDot = Arr::dot($defaults);
        $guardsConfig = static::config(static::GUARDS, []);

        // Staticaly cached default config 
        static::$config[static::DEFAULTS] = $defaultsAsDot;

        // Prepare per guard configuration
        foreach($guardsConfig as $guard => $guardConfig) {
            static::$config[$guard] = array_merge($defaultsAsDot, Arr::dot($guardConfig));
        }

        // Convert back to regulard array (from dot notation)
        foreach(static::$config as $group => $groupConfig) {
            $temp = [];

            foreach ($groupConfig as $key => $value) {
                Arr::set($temp, $key, $value);
            }

            static::$config[$group] = $temp;
        }
    }

    /**
     * Get config value for UserAuth
     *
     * @param string $key config key
     * @param mixed $default config default value
     * @param string|null $guard type of guard for which config will be returned
     * @return void
     */
    public static function get(string $key, $default = null, ?string $guard = null)
    {
        // Prepare config and staticaly cache
        if(!static::$config) {
            static::prepareConfig();
        }

        // Determine config group
        $configGroup = !$guard ? config('auth.defaults.guard', static::DEFAULTS) : $guard;
        
        // Determine if config per given group (guards) exists
        $configSource = array_key_exists($configGroup, static::$config) ? static::$config[$configGroup] : static::$config[static::DEFAULTS];
        
        // Return config value if exist
        return Arr::get($configSource, $key, $default);
    }
}