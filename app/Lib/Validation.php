<?php

namespace App\Lib;

class Validation
{
    const USERNAME = [
        "options" => [
            "regexp" => "/^[a-z0-9_-]+$/"
        ]
    ];

}