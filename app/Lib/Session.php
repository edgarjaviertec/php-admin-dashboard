<?php

namespace App\Lib;

class Session
{

    public function init()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public function add($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return !empty($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function getAll()
    {
        return $_SESSION;
    }

    public function remove($key)
    {
        if (!empty($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function close()
    {
        session_unset();
        session_destroy();
    }

    public function getStatus()
    {
        return session_status();
    }


    public function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = base64_encode(openssl_random_pseudo_bytes(32));
        }
    }
}