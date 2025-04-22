<?php

return [

    'default' => env('NOTIFICATION_DRIVER', 'database'),

    'channels' => [
        'mail' => [
            'driver' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
            'port' => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', null),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
        ],

        'database' => [
            'table' => 'notifications',
            'connection' => null,
        ],
    ],
];

