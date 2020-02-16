<?php

namespace App\UserAuth\Common;

use App\UserAuth\Captcha\Adapters\UserAuthCaptchaAdapterInterface;
use App\UserAuth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Config as LaravelConfig;

class Config
{
    const PREFIX = 'user-auth';
    
    /**
     * Current config group
     * @var string
     */
    protected ?string $guard = null;

    public function __construct(?string $guard = null)
    {
        // Determine config group if none is passed
        $this->guard = $guard ?: config('auth.defaults.guard', 'web');
    }

    public function get(?string $key = null, $default = null)
    {
        // Glue parts of config key together with dot notation
        $key = implode(".", [
            static::PREFIX,
            'guards',
            $this->guard,
            $key
        ]);

        // Trim excessive dot
        $key = rtrim($key, '.');

        // Get config from laravel config repository
        return LaravelConfig::get($key, $default);
    }

    //-----------------------------------------------------
    // Session related config accessors
    //-----------------------------------------------------

    /**
     * @return boolean
     */
    public function sessionLogEnabled(): bool
    {
        return $this->get('session_log', true);
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
     * Determine if verified email is required to log in
     * @return boolean
     */
    public function verifiedEmailRequired(): bool
    {
        return $this->get('login.verified_email_required', true);
    }

    //-----------------------------------------------------
    // E-mail verification related config accessors
    //-----------------------------------------------------
    
    /**
     * @return int
     */
    public function getEmailVerificationLinkExpireTime(): int
    {
        return $this->get('email_verification.link_expire_time', 120);
    }

    public function getEmailVerificationRoute(): string
    {
        return $this->get('email_verification.route', 'user-auth.verification.verify');
    }

    /**
     * @return boolean
     */
    public function emailVerificationEnabled(): bool
    {
        return $this->get('email_verification.enabled', false);
    }

    /**
     * Get notification class for e-mail verification
     *
     * @return string
     */
    public function getEmailVerificationNotification(): string
    {
        return $this->get('email_verification.notification', VerifyEmail::class);
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
