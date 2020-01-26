<?php

namespace App\UserAuth\Rules;

use App\UserAuth\Models\Blacklist;
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
        $blacklisted = Blacklist::whereType(Blacklist::T_PASSWORD)->whereValue($value)->exists();
        $length = mb_strlen($value);

        return $length >= 8 && $length <= 255 && !$blacklisted;
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