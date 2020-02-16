<?php

namespace App\UserAuth\Listeners\AuthSessionLog;

use App\UserAuth\Models\AuthSessionLog;
use App\UserAuth\Services\UserAuthService;
use Illuminate\Support\Facades\Auth;

class MarkAsLoggedOut
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle($event)
    {
        /** @var \App\UserAuth\Services\UserAuthService $userAuthService */
        $userAuthService = resolve(UserAuthService::class);
        $userAuthService->setGuard($event->guard);
        $userAuthService->handleSessionLog($event->user, true);
    }
}
