<?php


namespace App\UserAuth\Captcha\Adapters;

use Illuminate\Http\Request;
use ReCaptcha\ReCaptcha;

class GoogleCaptchaV3 extends AbstractCaptchaAdapter implements UserAuthCaptchaAdapterInterface
{
    public static string $tokenFieldName = '_captcha_token';

    public function render(Request $request): string
    {
        $publicKey = $this->getOptions()['public_key'] ?? null;

        if ($publicKey === null || empty($publicKey)) {
            throw new \RuntimeException("Captcha public key (public_key) must be passed via options and can not be empty.");
        }

        return '<script src="https://www.google.com/recaptcha/api.js?render='. $publicKey .'"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute("'. $publicKey .'", { action: "register" }).then(function(token) {
                $(\'[name="'. static::$tokenFieldName .'"]\').val(token);
                });
            });
        </script>
        <input type="hidden" name="'. static::$tokenFieldName .'" value/>';
    }

    public function validate(Request $request): bool
    {
        $privateKey = $this->getOptions()['private_key'] ?? null;

        if($privateKey === null || empty($privateKey)) {
            throw new \RuntimeException("Captcha private key (private_key) must be passed via options and can not be empty.");
        }

        $response = (new ReCaptcha($privateKey))
                    ->setExpectedAction('register')
                    ->setChallengeTimeout(5 * 60)
                    ->verify($request->input(static::$tokenFieldName), $request->ip());

        if ($response->isSuccess() && $response->getScore() > 0.6) {
            return true;
        }
    
        $this->setErrors($response->getErrorCodes());
        
        return false;
    }

    /**
     * @param array $errors
     * @return void
     */
    public function setErrors(array $errors): void
    {
        $this->errors = [];

        foreach($errors as $error) {
            $this->errors[] = __("auth.captcha.$error");
        }
    }
}