<?php

use App\UserAuth\Captcha\Adapters\GoogleCaptchaV3;
use App\UserAuth\Notifications\VerifyEmail;

/*
|--------------------------------------------------------------------------
| Settings of UserAuth 
|--------------------------------------------------------------------------
| 
| Settings from "defaults" and from given guard will be merged together (per guard config override defaults) 
| For eg. you can use same settings for captcha, but it can be enabled only in "web" guard
|
*/

// Defaults can be used for setting group config
$defaults = [
    'captcha' => [
        'enabled' => true,
        'adapter' => GoogleCaptchaV3::class,
        'options' => [
            'public_key' => env('GOOGLE_CAPTCHA_PUBLIC_KEY'),
            'private_key' => env('GOOGLE_CAPTCHA_PRIVATE_KEY'),
        ],
        'validation_message_key' => 'captchaValidationMessagesKey'
    ],

    'email_verification' => [
        // Determines if notification about e-mail verification will be send
        'enabled' => true,

        // Route to verification controller
        'route' => 'user-auth.verification.verify',

        // Notification class which will be used for sending verification request
        'notification' => VerifyEmail::class,

        // Expiration time in minutes for verification link
        'link_expire_time' => 120
    ],

    'login' => [
        // Determines if verified e-mail is required for logging in
        'verified_email_required' => true
    ],

    'registration' => [
        // Determine if user should be logged in automatically after registering
        'auto_login' => false,
    ]
    
];

return [
    // UserAuth config per group (can be guard named) 
    'groups' => [

        // Custom "web" guard related config (overrides those in "defaults")
        'web' => array_replace_recursive($defaults, [])

    ]
];
