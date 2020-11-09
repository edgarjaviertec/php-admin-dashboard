<?php

namespace App\Controllers;

use App\Lib\Session;
use App\Lib\TwigHelpers;
use App\Models\User;
use Plasticbrain\FlashMessages\FlashMessages;

class HomeController extends TwigHelpers
{
    private $user;
    private $session;
    private $msg;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->session = new Session();
        $this->msg = new FlashMessages;
    }

    public function index()
    {
        $data = [
            'titleTag' => "Demo - Página de inicio",
        ];
        echo $this->view->render('home.twig', $data);
    }
}