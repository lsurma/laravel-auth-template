<?php


namespace App\UserAuth\Captcha\Adapters;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use ReCaptcha\ReCaptcha;

class GoogleCaptchaV3 extends AbstractCaptchaAdapter implements UserAuthCaptchaAdapterInterface
{
    public function render(Request $request): string
    {
        $publicKey = $this->getOptions()['public_key'] ?? null;

        if( $publicKey === null) {
            throw new Exception("Captcha public key (public_key) must be passed via options.");
        }

        $hiddenFieldName = '_captcha_token';

        return '<script src="https://www.google.com/recaptcha/api.js?render='. $publicKey .'"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute("'. $publicKey .'", { action: "register" }).then(function(token) {
                $(\'[name="'. $hiddenFieldName .'"]\').val(token);
                });
            });
        </script>
        <input type="hidden" name="'. $hiddenFieldName .'" value/>';
    }

    public function validate(Request $request): bool
    {
        $privateKey = $this->getOptions()['private_key'] ?? null;

        if( $privateKey === null) {
            throw new Exception("Captcha private key (private_key) must be passed via options.");
        }

        $secret = 'secret';

        $response = (new ReCaptcha($secret))
                    ->setExpectedAction('register')
                    ->verify($request->input('_captcha_token'), $request->ip());

        if ($response->isSuccess() && $response->getScore() > 0.6) {
            return true;
        }
    
        return false;
    }
}