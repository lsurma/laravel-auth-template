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
            'public_key' => env('GOOGLE_CAPTCHA_PUBLIC_KEY'),
            'private_key' => env('GOOGLE_CAPTCHA_PRIVATE_KEY'),
        ]
    ]
];

return [
    // UserAuth config per group (can be guard named) 
    'groups' => [

        // Custom "web" guard related config (overrides those in "defaults")
        'web' => array_replace_recursive($defaults, [])

    ],

    // Forbidden passwords list (top 100 most used)
    'forbidden_passwords' => [
        '123456', 'password', '12345678', 'qwerty', '123456789', 
        '12345', '1234', '111111', '1234567', 'dragon', 
        '123123', 'baseball', 'abc123', 'football', 'monkey', 
        'letmein', '696969', 'shadow', 'master', '666666', 
        'qwertyuiop', '123321', 'mustang', '1234567890', 'michael', 
        '654321', 'pussy', 'superman', '1qaz2wsx', '7777777', 
        'fuckyou', '121212', '000000', 'qazwsx', '123qwe', 
        'killer', 'trustno1', 'jordan', 'jennifer', 'zxcvbnm', 
        'asdfgh', 'hunter', 'buster', 'soccer', 'harley', 
        'batman', 'andrew', 'tigger', 'sunshine', 'iloveyou',
        'fuckme', '2000', 'charlie', 'robert', 'thomas', 
        'hockey', 'ranger', 'daniel', 'starwars', 'klaster', 
        '112233', 'george', 'asshole', 'computer', 'michelle', 
        'jessica', 'pepper', '1111', 'zxcvbn', '555555', 
        '11111111', '131313', 'freedom', '777777', 'pass', 
        'fuck', 'maggie', '159753', 'aaaaaa', 'ginger', 
        'princess', 'joshua', 'cheese', 'amanda', 'summer', 
        'love', 'ashley', '6969', 'nicole', 'chelsea', 
        'biteme', 'matthew', 'access', 'yankees', '987654321', 
        'dallas', 'austin', 'thunder', 'taylor', 'matrix'
    ]
];
