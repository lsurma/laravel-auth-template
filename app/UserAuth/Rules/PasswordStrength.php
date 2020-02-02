<?php

namespace App\UserAuth\Rules;

use App\UserAuth\Models\Blacklist;
use Illuminate\Contracts\Validation\Rule;

class PasswordStrength implements Rule
{
    protected $message = null;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $length = mb_strlen($value);

        if($length < 8) {
            $this->message = __('validation.min.string', ['min' => 8]);
            return false;
        }

        if($length > 255) {
            $this->message = __('validation.max.string', ['max' => 255]);
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?: __('validation.password_strength');
    }
}