<?php

namespace App\UserAuth\Common\Interfaces;

use Illuminate\Notifications\Notification;

interface EmailVerificationNotificationInterface
{
    /**
     * Set route for verification link
     *
     * @param string $route
     * @return void
     */
    public function setVerificationRoute(string $route);

    /**
     * Set verification link expiration time (in minutes) 
     *
     * @param integer $minutes
     * @return void
     */
    public function setVerificationLinkExpireTime(int $minutes);

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable);

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable);
}