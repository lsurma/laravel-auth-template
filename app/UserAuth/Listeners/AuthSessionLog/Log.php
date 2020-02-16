<?php

namespace App\UserAuth\Listeners\AuthSessionLog;

use App\UserAuth\Models\AuthSessionLog;
use App\UserAuth\Services\UserAuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Log
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle($event)
    {
        /** @var \App\UserAuth\Services\UserAuthService $userAuthService */
        $userAuthService = resolve(UserAuthService::class);
        $userAuthService->setGuard($event->guard);
        $userAuthService->handleSessionLog($event->user, false);
    }
}
