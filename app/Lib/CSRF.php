<?php

namespace App\Lib;

class CSRF
{

    public function generateToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));
        }
    }
}