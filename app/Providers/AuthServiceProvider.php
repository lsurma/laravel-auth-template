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

        // Register custom session guard
        Auth::extend('session', function($app, $name, array $config) {
            return new SessionGuard($name, Auth::createUserProvider($config['provider']), $this->app['session.store']);
        });

        // Register custom users provider
        Auth::provider('user-auth-eloquent', function ($app, array $config) {
            return new UserProvider($app['hash'], $config['model']);
        });
    }
}
