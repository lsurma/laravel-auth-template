<?php

use App\UserAuth\Captcha\Handler\GoogleCaptchaV3;

return [

    /*
    |--------------------------------------------------------------------------
    | Settings per used auth guards
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [

        'captcha' => [
            'enabled' => true,
            'driver' => GoogleCaptchaV3::class,
            'options' => [
                
            ]
        ]
        
    ],

    'guards' => [
        'web' => [

        ]
    ],

];
