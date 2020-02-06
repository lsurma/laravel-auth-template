<?php

namespace App\UserAuth\Listeners;

use App\UserAuth\Common\Interfaces\EmailVerifiableInterface;
use App\UserAuth\Events\Registration\Registered as UserAuthRegistered;
use App\UserAuth\Services\UserAuthService;

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
        /** @var \App\UserAuth\Services\UserAuthService $userAuthService */
        $userAuthService = (resolve(UserAuthService::class));
        $userAuthService->setConfigGroup($event->eventData->configGroup);
        
        if (
            $userAuthService->config->emailVerificationEnabled()
            && $event->user instanceof EmailVerifiableInterface
            && !$event->user->hasVerifiedEmail()
        ) {
            $userAuthService->sendEmailVerificationNotification($event->user);
        }
    }
}
