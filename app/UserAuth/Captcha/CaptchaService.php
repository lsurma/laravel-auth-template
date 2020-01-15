<?php

namespace App\UserAuth\Captcha;

class CaptchaService
{
    public function setup(array $config)
    {

        // $captchaEnabled = config('user-auth.defaults.captcha.enabled', false);
        // $captchaHandlerClass = config('user-auth.defaults.captcha.handler', null);
        // $captchaHandler = $captchaHandlerClass ? app()->make($captchaHandlerClass) : null;
        // $captchaHandler->setOptions(['public_key' => '', 'private_key' => 'xd']);

    }

    public function isCaptchaEnabled(): bool
    {
        return true;
    }

    public function getCaptchaDriver()
    {

    }
    
    public function getValidationErrors(): array
    {
        return [];
    }

    public function render(): string
    {
        return '';
    }

    public function isRequestValid(): bool
    {
        return false;
    }
}