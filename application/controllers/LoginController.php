<?php

require __DIR__ . '/TwigBase.php';
require __DIR__ . '/../models/LoginModel.php';
require __DIR__ . '/../utils/arrays.php';
require_once __DIR__ . '/../../system/libs/session.php';

use Valitron\Validator;
use Plasticbrain\FlashMessages\FlashMessages;

class LoginController extends TwigBase
{

    private $model;
    private $session;
    private $msg;

    public function __construct()
    {
        parent::__construct();
        $this->model = new LoginModel();
        $this->session = new Session();
        $this->msg = new FlashMessages;

    }


    public function renderLogin()
    {
        if (!empty($this->session->get('logged_in_user'))) {
            header('Location: /backend');
            exit();
        }

        $data = [
            'titleTag' => "hola",
            'flashMessages' => $this->msg->display(null, false),
            'session' => json_encode($this->session->getAll(), JSON_PRETTY_PRINT)
        ];
        echo $this->view->render('login.twig', $data);
    }


    public function login()
    {
        $email = $_POST['inputEmail'];
        $password = $_POST['inputPassword'];
        $errorMessages = [];
        $user = $this->model->getUser($email);
        $userEmail = null;
        $userPassword = null;
        if ($user) {
            $userEmail = $user->email;
            $userPassword = $user->password;
        }
        $v = new Validator($_POST);
        $v->rule('required', ['inputEmail', 'inputPassword']);
        $v->rule('email', 'inputEmail');
        if ($v->validate()) {
            if ($email == $userEmail) {
                if ($password == $userPassword) {
                    $userInfo = [
                        "id" => $user->id,
                        "username" => $user->username,
                        "email" => $user->email
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
            $errorMessages = Arr::flat($v->errors());
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