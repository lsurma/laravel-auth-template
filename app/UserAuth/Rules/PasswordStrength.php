<?php

namespace App\UserAuth\Rules;

use App\UserAuth\Support\UserAuthConfig;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class PasswordStrength implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $forbiddenPasswords = UserAuthConfig::config('forbidden_passwords', []);
        $length = mb_strlen($value);

        return $length >= 8 && $length <= 255 && !in_array($value, $forbiddenPasswords);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.password_strength');
    }
}