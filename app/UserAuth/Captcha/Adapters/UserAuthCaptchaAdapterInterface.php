<?php

namespace App\UserAuth\Captcha\Adapters;

use Illuminate\Http\Request;

interface UserAuthCaptchaAdapterInterface
{
    /**
     * Undocumented function
     *
     * @param array $options
     * @return void
     */
    public function setOptions(array $options): void;

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getOptions(): array;

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getErrors(): array;

    /**
     * Undocumented function
     *
     * @param array $errors
     * @return void
     */
    public function setErrors(array $errors): void;

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return string
     */
    public function render(Request $request): string;
    
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return boolean
     */
    public function validate(Request $request): bool;
}