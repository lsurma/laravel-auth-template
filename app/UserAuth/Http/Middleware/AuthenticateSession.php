<?php

namespace App\UserAuth\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * Based on Laravel middleware, but instead of password hash as our session identifier,
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

        if (! $request->session()->has('session_auth_token')) {
            $this->storeSessionAuthToken($request);
        }

        if ($request->session()->get('session_auth_token') !== $request->user()->getSessionAuthToken()) {
            $this->logout($request);
        }

        return $next($request);
    }

    /**
     * Store the user's current password hash in the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function storeSessionAuthToken($request)
    {
        if (! $request->user()) {
            return;
        }

        $request->session()->put([
            'session_auth_token' => $request->user()->getSessionAuthToken(),
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
