<?php

namespace App\Config;

class Config
{
    const STATIC_PATHS = [
        'viewDir' => __DIR__ . '/../Views',
        'emailTemplates' => __DIR__ . '/../email-templates',
    ];

    const DB_CONFIG = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'my_dashboard',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8'
    ];


//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages

    const PHP_MAILER = [
        'SMTPDebug' => 0,
        'Host' => 'smtp.gmail.com',
        'SMTPAuth' => true,
        'Username' => 'edgartec87@gmail.com',
        'Password' => 'bdads2013',
        'SMTPSecure' => 'ssl',
        'Port' => 465,
        'setFromAddress' => 'edgartec87@gmail.com',
        'setFromName' => 'Mi Página',
    ];


    const JWT = [
        "secret" => 'sec!ReT423*&',
        "expiration" => 3600,
    ];
}