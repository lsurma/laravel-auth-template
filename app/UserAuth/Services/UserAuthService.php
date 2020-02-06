<?php

namespace App\UserAuth\Services;

use App\UserAuth\Common\Config;
use App\UserAuth\Common\Interfaces\EmailVerifiableInterface;
use App\UserAuth\Common\Interfaces\EmailVerificationNotificationInterface;

class UserAuthService
{
    /**
     * @var \App\UserAuth\Common\Config
     */
    public Config $config;
    
    public function __construct()
    {
    }

    public function setConfigGroup(string $configGroup)
    {
        $this->config = new Config($configGroup);

        return $this;
    }

    /**
     * Send e-mail verification notification based on configuration
     *
     * @param EmailVerifiableInterface $user
     * @return void
     */
    public function sendEmailVerificationNotification(EmailVerifiableInterface $user)
    {
        /** @var \Illuminate\Notifications\Notification $notification */
        $notification = resolve($this->config->getEmailVerificationNotification());

        if(!$notification) {
            throw new \Exception("Notification class not found in service container");
        }
            
        if($notification instanceof EmailVerificationNotificationInterface) {
            $notification->setVerificationRoute($this->config->getEmailVerificationRoute());
            $notification->setVerificationLinkExpireTime($this->config->getEmailVerificationLinkExpireTime());
        }

        return $user->sendEmailVerificationNotification($notification);
    }
}