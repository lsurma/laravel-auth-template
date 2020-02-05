<?php

namespace App\UserAuth\Listeners;

use App\UserAuth\Common\Config;
use App\UserAuth\Common\Interfaces\EmailVerifiableInterface;
use App\UserAuth\Common\Interfaces\EmailVerificationNotificationInterface;
use App\UserAuth\Events\Registration\Registered as UserAuthRegistered;
use Illuminate\Notifications\Notification;
use RuntimeException;

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
        $config = new Config($event->eventData->configGroup);

        if (
            $config->emailVerificationEnabled()
            && $event->user instanceof EmailVerifiableInterface
            && !$event->user->hasVerifiedEmail()
        ) {
            /** @var Notification $notification */
            $notification = resolve($config->getEmailVerificationNotification());

            if(!$notification) {
                throw new RuntimeException("Notification class not found in service container");
            }
            
            if($notification instanceof EmailVerificationNotificationInterface) {
                $notification->setVerificationRoute($config->getEmailVerificationRoute());
                $notification->setVerificationLinkExpireTime($config->getEmailVerificationLinkExpireTime());
            }

            $event->user->sendEmailVerificationNotification($notification);
        }
    }
}
