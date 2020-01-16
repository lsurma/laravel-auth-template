<?php

use App\UserAuth\Captcha\Adapters\GoogleCaptchaV3;

return [

    /*
    |--------------------------------------------------------------------------
    | Settings of UserAuth 
    |--------------------------------------------------------------------------
    | 
    | Settings from "defaults" and from given guard will be merged together (per guard config override defaults) 
    | For eg. you can use same settings for captcha, but it can be enabled only in "web" guard
    |
    */

    // Default config
    'defaults' => [

        'captcha' => [
            'enabled' => true,
            'adapter' => GoogleCaptchaV3::class,
            'options' => [
                'public_key' => '',
                'private_key' => ''
            ]
        ]
        
    ],

    // Per guard config 
    'guards' => [

        // Custom "web" guard related config (overrides those in "defaults")
        'web' => [
            'captcha' => [
                'enabled' => false,
                'options' => [
                    'public_key' => 'test',
                    'private_key' => ''
                ]
            ]
        ],

    ],

];
