<?php

return [
    'app_views_folder' => __DIR__ . '/Views',
    'app_email_templates_folder' => __DIR__ . '/email-templates',
    'db_driver' => env('DB_DRIVER'),
    'db_host' => env('DB_HOST'),
    'db_port' => env('DB_PORT'),
    'db_database' => env('DB_DATABASE'),
    'db_username' => env('DB_USERNAME'),
    'db_password' => env('DB_PASSWORD'),
    'db_charset' => env('DB_CHARSET'),
    'mail_debug' => env('MAIL_DEBUG'),
    'mail_host' => env('MAIL_HOST'),
    'mail_port' => env('MAIL_PORT'),
    'mail_auth' => true,
    'mail_username' => env('MAIL_USERNAME'),
    'mail_password' => env('MAIL_PASSWORD'),
    'mail_encryption' => env('MAIL_ENCRYPTION'),
    'mail_from_address' => env('MAIL_FROM_ADDRESS'),
    'mail_from_name' => env('MAIL_FROM_NAME'),
    'jwt_secret' => env('JWT_SECRET'),
    'jwt_expiration' => env('JWT_EXPIRATION'),
];