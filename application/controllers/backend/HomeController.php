<?php

require_once __DIR__ . '/../TwigBase.php';
require_once __DIR__ . '/../../../system/libs/session.php';

class HomeController extends TwigBase
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
        $this->session->init();
        if ($this->session->getStatus() === 1 || empty($this->session->get('logged_in_user'))) {
            header('Location: /login');
            exit();
        }
    }


    function index()
    {
        if ($_SESSION['logged_in_user']) {
            echo "<h1>Si existe la sesion</h1>";
            echo "<pre>";
            var_dump($_SESSION['logged_in_user']);
            echo "</pre>";
            echo "<br>";
            echo "<a href='/logout?csrf=".  urlencode($_SESSION['csrf']) . "'>Cerrar sesión</a>";
        } else {
            echo "<h1>No existe la sesion</h1>";
        }
    }





}