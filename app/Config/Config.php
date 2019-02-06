<?php

namespace App\Config;

class Config
{
    const STATIC_PATHS = [
        'viewDir' => __DIR__ . '/../Views'

    ];

    const DB_CONFIG = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'my_dashboard',
        'username' => 'root',
        'password' => 'root',
        'charset'=>'utf8'
    ];
}