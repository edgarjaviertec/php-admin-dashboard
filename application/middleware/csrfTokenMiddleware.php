<?php


require_once __DIR__ . "/../../system/libs/session.php";

class CsrfTokenMiddleware
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }


    public function verifyCsrfToken(){
        if (isset($_GET["csrf"]) && !empty($this->session->get('csrf')) &&  $_GET["csrf"] === $this->session->get('csrf')   ) {
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