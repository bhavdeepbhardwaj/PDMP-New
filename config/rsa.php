<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Login RSA Encryption
    |--------------------------------------------------------------------------
    |
    | RSA public key is exposed to the browser.
    | RSA private key must remain server-side.
    |
    */

    'login' => [

        'private_key' => storage_path(
            'app/private/rsa/login-private.pem'
        ),

        'public_key' => storage_path(
            'app/private/rsa/login-public.pem'
        ),

    ],

];