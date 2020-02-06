<?php

namespace App\UserAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use App\UserAuth\Services\UserAuthService;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use RedirectsUsers;

    /**
     * Where to redirect users after verification.
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
     * @var \App\UserAuth\Services\UserAuthService
     */
    protected UserAuthService $userAuthSerivce;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');

        // Throttle max attempts to resend email verification notice
        $this->middleware('throttle:5,15')->only('verify', 'resend');

        $this->userAuthSerivce = resolve(UserAuthService::class);
        $this->userAuthSerivce->setConfigGroup($this->configGroup);

        $this->redirectTo = route('user-auth.login');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        /** @var \Illuminate\Foundation\Auth\User $user */
        $user = $this->guard()->user();

        return $user && $user->hasVerifiedEmail()
                    ? redirect($this->redirectPath())
                    : view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        /** @var \Illuminate\Foundation\Auth\User $user */
        $user = $this->userProvider()->retrieveById($request->route('id'));

        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $email = $request->request->get('email');

        /**     
         * Try to find user based on provided e-mail address
         * If user is not found, or it's email is already verified we should not inform user about this
         * Because this can leak informations about our user database
         * 
         * @var \App\UserAuth\Common\Interfaces\EmailVerifiableInterface $user
         */
        $user = $this->userProvider()->retrieveByCredentials([
            'email' => $email
        ]);

        // Resend e-mail only if user is found and his email is not verified
        if ($user && !$user->hasVerifiedEmail()) {
            $this->userAuthSerivce->sendEmailVerificationNotification($user);
        }

        // Back to same page with simple message
        return back()->with('resent', true);
    }

    /**
     * Get the guard to be used during email verification.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard|\Illuminate\Auth\SessionGuard
     */
    protected function guard()
    {
        return Auth::guard($this->guard);
    }

    /**
     * Get user provider for current guard.
     *
     * @return \Illuminate\Contracts\Auth\UserProvider
     */
    protected function userProvider()
    {
        return $this->guard()->getProvider();
    }
}
