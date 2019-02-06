<?php

namespace App\Controllers;
use App\Lib\Session;
use App\Lib\TwigHelpers;
use App\Models\User;
use Valitron\Validator;
use Plasticbrain\FlashMessages\FlashMessages;
use App\Utils\Arrays;

class LoginController extends TwigHelpers
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


    public function renderLogin()
    {
        if (!empty($this->session->get('logged_in_user'))) {
            header('Location: /Backend');
            exit();
        }

        $data = [
            'titleTag' => "hola",
            'flashMessages' => $this->msg->display(null, false)
        ];
        echo $this->view->render('login.twig', $data);
    }


    public function login()
    {
        $email = $_POST['inputEmail'];
        $password = $_POST['inputPassword'];
        $errorMessages = [];
        $user = $this->user->getByEmail($email);
        $userEmail = null;
        $hash = null;
        if ($user) {
            $userEmail = $user["email"];
            $hash = $user["password"];
        }
        $v = new Validator($_POST);
        $v->rule('required', ['inputEmail', 'inputPassword']);
        $v->rule('email', 'inputEmail');
        if ($v->validate()) {


            if ($email == $userEmail) {

                // password_verify($password, $hash)
                if ( password_verify($password, $hash) ) {
                    $userInfo = [
                        "id" => $user["id"],
                        "username" => $user["username"],
                        "email" => $user["email"]
                    ];
                    $this->session->add('logged_in_user', $userInfo);
                    header('Location: /backend');
                    exit();
                } else {
                    $this->msg->error("Invalid password", '/login');
                }
            } else {
                $this->msg->error("Invalid email address", '/login');
            }




        } else {
            $errorMessages = Arrays::flat($v->errors());
            foreach ($errorMessages as $key => $error) {
                if ($key == count($errorMessages) - 1) {
                    $this->msg->error(strval($error), '/login');
                } else {
                    $this->msg->error(strval($error));
                }
            }
        }
    }


    public function logout()
    {
        $this->session->close();
        header('Location: /login');
        exit();
    }
}