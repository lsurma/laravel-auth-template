<?php

use App\UserAuth\Captcha\Adapters\GoogleCaptchaV3;

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
            'public_key' => '',
            'private_key' => ''
        ]
    ]
];

return [
    // UserAuth config per group (can be guard named) 
    'groups' => [

        // Custom "web" guard related config (overrides those in "defaults")
        'web' => array_replace_recursive($defaults, [
            'captcha' => [
                'enabled' => false,
                'options' => [
                    'public_key' => 'test',
                    'private_key' => ''
                ]
            ]
        ])

    ]
];
