<?php

namespace App\UserAuth\Common;

use App\UserAuth\Captcha\Adapters\UserAuthCaptchaAdapterInterface;
use Illuminate\Support\Facades\Config as LaravelConfig;

class Config
{
    const PREFIX = 'user-auth';
    
    /**
     * Current config group
     * @var string
     */
    protected ?string $group = null;

    public function __construct(?string $group = null)
    {
        // Determine config group if none is passed
        $this->group = $group ?: config('auth.defaults.guard', 'web');
    }

    public function get(?string $key = null, $default = null)
    {
        // Glue parts of config key together width dot notation
        $key = implode(".", [
            static::PREFIX,
            'groups',
            $this->group,
            $key
        ]);

        // Trim excessive dot
        $key = rtrim($key, '.');

        // Get config from laravel config repository
        return LaravelConfig::get($key, $default);
    }

    //-----------------------------------------------------
    // Registration related config accessors
    //-----------------------------------------------------

    /**
     * @return boolean
     */
    public function autoLoginAfterRegistrationEnabled(): bool
    {
        return $this->get('registration.auto_login', true);
    }

    //-----------------------------------------------------
    // Login related config accessors
    //-----------------------------------------------------
    
    /**
     * @return boolean
     */
    public function verifiedEmailForLoginRequired(): bool
    {
        return $this->get('login.verified_email_required', true);
    }

    //-----------------------------------------------------
    // Captcha related config accessors
    //-----------------------------------------------------

    /**
     * @return boolean
     */
    public function captchaEnabled(): bool
    {
        return $this->get('captcha.enabled', false);
    }

    /**
     *
     * @param array $default
     * @return array
     */
    public function getCaptchaOptions(array $default = []): array
    {
        return $this->get('captcha.options', $default);
    }

    /**
     * @param string|null $default
     * @return string|null
     */
    public function getCaptchaAdapter(?string $default = null): ?string
    {
        return $this->get('captcha.adapter', $default);
    }
    
    /**
     * @param string $default
     * @return string
     */
    public function getCaptchaValidationMessageKey(string $default = 'captchaValidationMessagesKey'): string
    {
        return $this->get('captcha.validation_message_key', $default);
    }
}