<?php

namespace App\UserAuth\Rules;

use App\UserAuth\Models\Blacklist;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class BlacklistEmail implements Rule
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
        $emailDomain = Str::afterLast($value, "@");
        $domainBlacklisted = Blacklist::whereType(Blacklist::T_DOMAIN_EMAIL)->whereValue($emailDomain)->exists();
        $emailBlacklisted = Blacklist::whereType(Blacklist::T_EMAIL)->whereValue($value)->exists();
        
        return !$domainBlacklisted && !$emailBlacklisted;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.blacklist_email');
    }
}