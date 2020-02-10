<?php

namespace App\UserAuth\Guards;

use App\UserAuth\Common\Interfaces\HasSessionAuthInterface;

class SessionGuard extends \Illuminate\Auth\SessionGuard
{
    /**
     * Invalidate other sessions for the current user.
     *
     * The application must be using the AuthenticateSession middleware.
     *
     * @param  string  $password
     * @param  string  $attribute
     * @return bool|null
     */
    public function logoutOtherDevices($password = null, $attribute = null)
    {
        /** @var \Illuminate\Foundation\Auth\User $user */
        $user = $this->user();
        $result = false;
        
        if (!$user) {
            return;
        }

        if($user instanceof HasSessionAuthInterface) {
            $result = $user->regenerateSessionAuthToken();
        }

        $this->fireOtherDeviceLogoutEvent($user);

        return $result;
    }
}