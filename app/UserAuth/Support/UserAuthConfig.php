<?php

namespace App\UserAuth\Support;

use Illuminate\Support\Arr;

/**
 * TODO: Method namings can be misleading  
 */
class UserAuthConfig
{
    const PREFIX = 'user-auth';

    /**
     * Proxy access for configuration values of UserAuth scope
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed|\Illuminate\Config\Repository
     */
    public static function config(?string $key = null, $default = null)
    {
        return config(static::PREFIX . '.'  . ltrim($key, "."), $default);
    }
    
    /**
     * Get config value for UserAuth
     *
     * @param string|null $key config key
     * @param mixed $default config default value
     * @param string|null $group type of group for which config will be returned
     * @return void
     */
    public static function get(?string $key = null, $default = null, ?string $group = null)
    {
        // Determine config group and key 
        $configGroup = !$group ? config('auth.defaults.guard', null) : $group;

        if($configGroup === null) {
            return static::all();
        }

        return static::config(rtrim('groups.' . $configGroup . '.' . $key, '.'), $default);
    }

    /**
     * Get all config values related to UserAuth 
     * @return mixed|\Illuminate\Config\Repository
     */
    public static function all()
    {
        return config(static::PREFIX, []);
    }
}