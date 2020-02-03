<?php

namespace App\UserAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserAuth\Captcha\Adapters\UserAuthCaptchaAdapterInterface;
use App\UserAuth\Events\EventData;
use App\UserAuth\Events\EventParams;
use App\UserAuth\Events\Registration\Registered as UserAuthRegistered;
use App\UserAuth\Rules\PasswordStrength;
use App\UserAuth\Support\UserAuthConfig;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
     */
    protected string $guard = 'web';

    /**
     * Captcha adapter
     * @var UserAuthCaptchaAdapterInterface
     */
    protected UserAuthCaptchaAdapterInterface $captcha;
    
    /**
     * Determine if captcha is enabled, based on configuration values
     * @var bool 
     */
    protected bool $captchaEnabled = false;

    /**
     * Captcha validation message key. Used in in error messages and templates
     * @var string
     */
    protected static string $captchaValidationMessagesKey = 'userAuthCaptcha';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        // Prepare captcha 
        $this->captchaEnabled = UserAuthConfig::get('captcha.enabled', false, $this->configGroup);

        if($this->captchaEnabled) {
            $this->captcha = resolve(UserAuthConfig::get('captcha.adapter'));
            $this->captcha->setOptions((array)UserAuthConfig::get('captcha.options', []));
        }
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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        return view('auth.register', [
            'catpcha' => [
                'enabled' => $this->captchaEnabled,
                'output' => $this->captchaEnabled ? $this->captcha->render($request) : '',
                'validationMessagesKey' => static::$captchaValidationMessagesKey
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
        $this->validator($request->all())->validate();

        /**
         * @TODO: Move to separte method and user "after" validation hook
         */
        if($this->captchaEnabled && !$this->captcha->validate($request)) {
            $captchaErrors = $this->captcha->getErrors() ?: [__('auth.captcha.error')];

            throw ValidationException::withMessages([
                static::$captchaValidationMessagesKey => $captchaErrors
            ]);
        }

        // Create user
        $user = $this->create($request->all());

        // Emit original laravel Registered event
        event(new Registered($user));

        // Emit UserAuth package registered event with some aditional data
        event(new UserAuthRegistered($user, new EventData($this->guard, $this->configGroup)));

        // Log in user automatically
        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
}
