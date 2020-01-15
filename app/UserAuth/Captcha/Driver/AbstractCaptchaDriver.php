<?php


namespace App\UserAuth\Captcha\Driver;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use ReCaptcha\ReCaptcha;

class AbstractCaptchaDriver implements UserAuthCaptchaDriverInterface
{
    protected array $errors = [];
    protected array $options = [];

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Undocumented function
     *
     * @param array $errors
     * @return void
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Undocumented function
     *
     * @param array $options
     * @return void
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * Undocumented function
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function render(\Illuminate\Http\Request $request): string
    {
        return '';
    }

    /**
     * Undocumented function
     *
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    public function validate(\Illuminate\Http\Request $request): bool
    {
        return false;
    }
}