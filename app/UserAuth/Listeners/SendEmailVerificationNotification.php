<?php

namespace App\UserAuth\Listeners;

use App\UserAuth\Common\Interfaces\EmailVerifiableInterface;
use App\UserAuth\Events\Registration\Registered as UserAuthRegistered;
use Illuminate\Foundation\Auth\User;

class SendEmailVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(UserAuthRegistered $event)
    {
        $emailVerificationEnabled = true;
        
        if (
            $emailVerificationEnabled 
            && $event->user instanceof EmailVerifiableInterface
            && !$event->user->hasVerifiedEmail()
        ) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}
