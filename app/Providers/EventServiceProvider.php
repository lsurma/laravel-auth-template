<?php

namespace App\Providers;

use App\UserAuth\Events\Registration\Registered as UserAuthRegistered;
use App\UserAuth\Listeners\SendEmailVerificationNotification as UserAuthSendEmailVerificationNotification;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        UserAuthRegistered::class => [
            UserAuthSendEmailVerificationNotification::class
        ],

        Authenticated::class => [
            \App\UserAuth\Listeners\AuthSessionLog\Log::class
        ],

        Logout::class => [
            \App\UserAuth\Listeners\AuthSessionLog\MarkAsLoggedOut::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
