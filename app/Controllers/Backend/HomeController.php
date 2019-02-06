<?php


namespace App\Controllers\Backend;

use App\Lib\TwigHelpers;
use App\Lib\Session;

use Plasticbrain\FlashMessages\FlashMessages;

class HomeController extends TwigHelpers
{
    private $session;
    public function __construct()
    {
        parent::__construct();
        $this->session = new Session();
        $this->msg = new FlashMessages;
        if ($this->session->getStatus() === 1 || empty($this->session->get('logged_in_user'))) {
            header('Location: /login');
            exit();
        }
    }

    public function index()
    {
        $data = [
            "titleTag" => "Usuarios"
        ];
        echo $this->view->render('Backend/home.twig', $data);
    }


}

