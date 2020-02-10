<?php

namespace App\Providers;

use App\UserAuth\Guards\SessionGuard;
use App\UserAuth\Providers\UserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        // Register custom session guard basend on laravels one
        Auth::extend('session', function($app, $name, array $config) {
            $guard = new SessionGuard(
                $name, 
                Auth::createUserProvider($config['provider'] ?? null), 
                $app['session.store']
            );

            // When using the remember me functionality of the authentication services we
            // will need to be set the encryption instance of the guard, which allows
            // secure, encrypted cookie values to get generated for those cookies.
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }

            return $guard;
        });

        // Register custom users provider
        Auth::provider('user-auth-eloquent', function ($app, array $config) {
            return new UserProvider($app['hash'], $config['model']);
        });
    }
}
