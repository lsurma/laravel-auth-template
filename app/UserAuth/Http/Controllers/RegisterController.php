<?php

namespace App\UserAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserAuth\Captcha\Adapters\UserAuthCaptchaAdapterInterface;
use App\UserAuth\Rules\BlacklistEmail;
use App\UserAuth\Rules\PasswordStrength;
use App\UserAuth\Support\UserAuthConfig;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    protected UserAuthCaptchaAdapterInterface $captcha;
    
    protected bool $captchaEnabled = false;

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
        $this->captchaEnabled = UserAuthConfig::get('captcha.enabled', false);
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new BlacklistEmail],
            'password' => ['required', 'string', 'confirmed', new PasswordStrength],
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

        if($this->captchaEnabled && !$this->captcha->validate($request)) {
            $captchaErrors = $this->captcha->getErrors() ?: [__('auth.captcha.error')];

            throw ValidationException::withMessages([
                static::$captchaValidationMessagesKey => $captchaErrors
            ]);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
}
