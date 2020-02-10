<?php

namespace App\UserAuth\Http\Middleware;

use App\UserAuth\Common\Interfaces\HasSessionAuthInterface;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * Based on Laravel middleware (\Illuminate\Session\Middleware\AuthenticateSession::class), but instead of password hash as our session identifier,
 * we will be using custom property called "session_auth_token" 
 */
class AuthenticateSession
{
    /**
     * The authentication factory implementation.
     *
     * @var \Illuminate\Auth\SessionGuard|\Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Key under which session auth token will be stored
     *
     * @var string
     */
    protected $sessionAttributeKey = 'session_auth_token';

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->hasSession() || ! $request->user()) {
            return $next($request);
        }

        if (! $request->session()->has($this->sessionAttributeKey)) {
            $this->storeSessionAuthToken($request);
        }

        if ($request->session()->get($this->sessionAttributeKey) !== $request->user()->getSessionAuthToken()) {
            $this->logout($request);
        }

        return tap($next($request), function () use ($request) {
            $this->storeSessionAuthToken($request);
        });
    }

    /**
     * Store the user's current password hash in the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function storeSessionAuthToken($request)
    {
        /** @var HasSessionAuthInterface $user */
        $user = $request->user();

        if (!$user) {
            return;
        }

        // Get user token, and check if its not falsey value
        // if it is, then regenerate token
        if(!$token = $user->getSessionAuthToken()) {
            $token = tap($user, function($user) {
                $user->regenerateSessionAuthToken();
            })->getSessionAuthToken();
        }

        $request->session()->put([
            $this->sessionAttributeKey => $token,
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function logout($request)
    {
        $this->auth->logoutCurrentDevice();

        $request->session()->flush();

        throw new AuthenticationException;
    }
}
