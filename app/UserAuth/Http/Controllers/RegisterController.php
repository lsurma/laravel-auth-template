<?php

namespace App\UserAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserAuth\Captcha\Adapters\UserAuthCaptchaAdapterInterface;
use App\UserAuth\Common\Config;
use App\UserAuth\Events\EventData;
use App\UserAuth\Events\Registration\Registered as UserAuthRegistered;
use App\UserAuth\Rules\PasswordStrength;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use LSurma\LaravelBlacklist\Rules\EmailAllowed;
use LSurma\LaravelBlacklist\Rules\PasswordAllowed;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
     * Captcha adapter
     * @var UserAuthCaptchaAdapterInterface
     */
    protected ?UserAuthCaptchaAdapterInterface $captchaAdapter = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        // UserAuthConfig support object
        $this->config = new Config($this->configGroup);
        
        // Prepare captcha 
        if($this->config->captchaEnabled()) {
            // Resolve captcha adapter from service container
            $this->captchaAdapter = resolve($this->config->getCaptchaAdapter());
            
            // Pass options from captcha config to captcha adapter
            $this->captchaAdapter->setOptions(
                $this->config->getCaptchaOptions()
            );
        }
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        return view('auth.register', [
            'catpcha' => [
                'enabled' => $this->config->captchaEnabled(),
                'output' => $this->config->captchaEnabled() ? $this->captchaAdapter->render($request) : '',
                'validationMessagesKey' => $this->config->getCaptchaValidationMessageKey()
            ]
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Run defined validators, if there will be errors, exception will be thrown
        $this->validator($request->all())
             ->after(function(ValidatorContract $validator) use ($request) {
                $this->validateCaptcha($request, $validator);
             })
             ->validate();

        // Create user
        $user = $this->create($request->all());

        // Emit original laravel Registered event
        event(new Registered($user));

        // Emit UserAuth package registered event with some aditional data
        event(new UserAuthRegistered(
            $user, 
            new EventData($this->guard, $this->configGroup)
        ));

        // Log in user automatically if enabled
        if($this->config->loginAfterRegistrationEnabled()) {
            $this->guard()->login($user);
        }

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', new EmailAllowed, 'unique:users'],
            'password' => ['required', 'string', 'confirmed', new PasswordStrength, new PasswordAllowed],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard($this->guard);
    }

    /**
     * @param Request $request
     * @param ValidatorContract $validator
     * @return void|bool
     */
    protected function validateCaptcha(Request $request, ValidatorContract $validator)
    {
        // If captcha is not enabled, or if is validated successfully return true
        if(!$this->config->captchaEnabled() 
           || $this->captchaAdapter->validate($request) 
        ) {
            return true;
        }

        // In other case prepare errors and pass it to given validator instance
        $errors = $this->captchaAdapter->getErrors() ?: [__('auth.captcha.error')];

        foreach($errors as $error) {
            $validator->errors()->add(
                $this->config->getCaptchaValidationMessageKey(), 
                $error
            );
        }
    }
}
