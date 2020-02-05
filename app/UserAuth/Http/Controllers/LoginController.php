<?php

namespace App\UserAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\UserAuth\Common\Config;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Config group
     * @var string 
     */
    protected string $configGroup = 'web';

    /**
     * Guard used
     * @var string
     */
    protected string $guard = 'web';

    /**
     * @var \App\UserAuth\Common\Config
     */
    protected Config $config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        // UserAuthConfig support object
        $this->config = new Config($this->configGroup);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        // Basic credentials from form request
        $credentials = $request->only($this->username(), 'password');

        // Custom credentials requirements via closure
        if ($this->config->verifiedEmailRequired()) {
            $credentials[] = function(Builder $query) {
                $query->whereNotNull('email_verified_at');
            };
        }

        return $credentials;
    }
}
