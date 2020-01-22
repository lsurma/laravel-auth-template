<?php

namespace App\UserAuth\Captcha\Adapters;

use Illuminate\Http\Request;

interface UserAuthCaptchaAdapterInterface
{
    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options): void;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @param array $errors
     * @return void
     */
    public function setErrors(array $errors): void;

    /**
     * @param Request $request
     * @return string
     */
    public function render(Request $request): string;
    
    /**
     * @param Request $request
     * @return boolean
     */
    public function validate(Request $request): bool;
}