<?php

namespace App\UserAuth\Captcha;

class CaptchaService
{
    public function setup(array $config)
    {
        
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