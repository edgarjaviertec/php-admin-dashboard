<?php

namespace App\Middleware;
use App\Lib\Session;

class CsrfTokenMiddleware
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }


    public function verifyCsrfToken(){
        if (isset($_GET["csrf"]) && !empty($this->session->get('csrf_token')) &&  $_GET["csrf"] === $this->session->get('csrf_token')   ) {
            header("HTTP/1.1 500 OK");

        }else{
            header('HTTP/1.1 403 Forbidden');
            echo "CSRF verification failed. Requested aborted";
//            echo "GET: " . $_GET["csrf"] . "<br>";
//            echo "SESSION: " . $this->session->get('csrf');
            die();
        }
    }
}