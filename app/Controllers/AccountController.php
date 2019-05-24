<?php

namespace App\Controllers;

use Firebase\JWT\ExpiredException;
use Plasticbrain\FlashMessages\FlashMessages;
use Firebase\JWT\JWT;

use App\Config\Config;

use App\Lib\Sanitization;
use App\Lib\Validation;
use App\Lib\MyJWT;
use App\Lib\MyMailer;
use App\Lib\Session;
use App\Lib\TwigHelpers;

use App\Models\User;
use App\Models\Role;


class AccountController extends TwigHelpers
{
    private $user;
    private $session;
    private $msg;

    private $role;
    private $sanitization;


    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->session = new Session();
        $this->msg = new FlashMessages;

        $this->role = new Role();
        $this->sanitization = new Sanitization();
    }

    public function showLogin()
    {
        if (!empty($this->session->get('logged_in_user'))) {
            header('Location: /Backend');
            exit();
        }

        $data = [
            'titleTag' => "Inicia sesión",
            'flashMessages' => $this->msg->display(null, false)
        ];
        echo $this->view->render('login.twig', $data);
    }

    public function login()
    {

        $errors = [];


        if (empty($_POST["email"])) {
            array_push($errors, "El campo de correo electrónico es requerido");
        }

        if (empty($_POST["password"])) {
            array_push($errors, "El campo de contraseña es requerido");
        }


        if (count($errors) > 0) {
            $data = [
                "messages" => $errors
            ];
            $errorMessages = $this->view->render('misc/flash-messages.twig', $data);
            $this->msg->error($errorMessages, BASE_URL . "/login");
        } else {

            $this->session->add("old", $_POST);


            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $this->msg->error("El correo del usuario no es valido", BASE_URL . '/login');


            } else {
                $user = $this->user->getByEmail($email);
                $hash = $user["password"];
                $userId = $user["id"];
                $emailVerified = $this->user->checkIfEmailIsVerified($userId);

                if (!$user) {
                    $this->msg->error("La dirección de correo electrónico que introdujo no existe", BASE_URL . "/login");
                } else {


                    if ($emailVerified) {


                        if (password_verify($password, $hash)) {
                            $userInfo = [
                                "id" => $user["id"],
                                "username" => $user["username"],
                                "email" => $user["email"]
                            ];


                            $this->session->add('logged_in_user', $userInfo);

                            // Quitamos los datos del formulario de la sesión
                            $this->session->remove("old");

                            header("Location: " . BASE_URL . "/backend");
                            exit();
                        } else {
                            $this->msg->error("La contraseña es incorrecta", BASE_URL . '/login');
                        }


                    } else {
                        $this->msg->error("No puede iniciar sesión si no ha verificado su correo electrónico. Por favor, revise su correo electrónico para activarlo.", BASE_URL . "/login");
                    }


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

    public function resendEmaiVerification()
    {
        if (isset($_POST)) {

            $email = $_POST["email"];


            $user = $this->user->getByEmail($email);

            if ($user) {


                $userId = $user["id"];
                $username = $user["username"];


                $payload = array(
                    'userid' => $userId,
                    'exp' => time() + Config::JWT["expiration"]
                );
                $key = Config::JWT["secret"];
                $alg = 'HS256';
                $token = JWT::encode($payload, $key, $alg);


                $mailer = new MyMailer(true);                              // Passing `true` enables exceptions
                $link = BASE_URL . "user-verification/" . $token;

                try {
                    $data = [
                        "username" => $username,
                        "link" => $link
                    ];
                    $html = $this->emailTemplate->render('email-confirmation.twig', $data);
                    $mailer->addAddress($email);
                    $mailer->isHTML(true);
                    $mailer->Subject = 'Verifica tu dirección de correo electrónico';
                    $mailer->Body = $html;
                    $mailer->send();


                    $this->msg->success("Se envío un correo electrónico de verificación. Por favor, revisa tu correo electrónico.", BASE_URL . "login");
                } catch (Exception $e) {
                    $this->msg->error('No se pudo enviar el mensaje. Error del remitente: ', $mailer->ErrorInfo, BASE_URL . "register");
                }


            }


        }
    }

    public function showResetPassword($token)
    {


        $key = Config::JWT["secret"];


        try {
            $tokenToVerify = $token;
            $decodedToken = JWT::decode($tokenToVerify, $key, array('HS256'));
            $userId = $decodedToken->userid;


            $data = [
                'titleTag' => "Restablecer la contraseña",
                'flashMessages' => $this->msg->display(null, false),
                "token" => $token
            ];
            echo $this->view->render('account/reset-password.twig', $data);


        } catch (ExpiredException $e) {
            $erroMessage = $e->getMessage();
            $data = [
                'titleTag' => "El enlace ha expirado",
                'flashMessages' => $this->msg->display(null, false),
                'error' => $erroMessage
            ];
            echo $this->view->render('account/reset-password-link-expired.twig', $data);
        } catch (\Exception $e) {
            $erroMessage = $e->getMessage();
            $data = [
                'titleTag' => "Token no válido",
                'flashMessages' => $this->msg->display(null, false),
                'error' => $erroMessage
            ];
            echo $this->view->render('account/invalid-token.twig', $data);
        }


    }

    public function resetPassword($token)
    {


        $errors = [];


        if (empty($_POST["password"])) {
            array_push($errors, "El campo de contraseña es requerido");
        }
        if (empty($_POST["confirmPassword"])) {
            array_push($errors, "El campo para confirmar la contraseña es requerido");
        }

        if (count($errors) > 0) {
            $data = [
                "messages" => $errors
            ];
            $errorMessages = $this->view->render('misc/flash-messages.twig', $data);
            $this->msg->error($errorMessages, BASE_URL . "/reset-password/" . $token);

        } else {
            $password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_STRING);
            $confirmPassword = trim($_POST["confirmPassword"], FILTER_SANITIZE_STRING);
            if (strcmp($password, $confirmPassword) !== 0) {
                $this->msg->error("La contraseña del usuario no coincide.", BASE_URL . "/reset-password/" . $token);

            } else {
                $myJWT = new MyJWT();
                $payLoad = $myJWT->getPayload($token);
                $user = $this->user->getById($payLoad->userid);
                if ($user) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $this->user->changePassword($user["id"], $hashedPassword);
                    $this->msg->success("Su contraseña se ha actualizado. Puede iniciar sesión.", BASE_URL . "/login");
                } else {
                    $data = [
                        'titleTag' => "Error",
                        'flashMessages' => $this->msg->display(null, false),
                    ];
                    echo $this->view->render('account/invalid-token.twig', $data);
                }
            }

        }


    }

    public function showForgotPassword()
    {

        $data = [
            'titleTag' => "¿Olvidaste tu contraseña?",
            'flashMessages' => $this->msg->display(null, false),
        ];
        echo $this->view->render('account/forgot-password.twig', $data);
    }

    public function forgotPassword()
    {


        if (!empty($_POST["email"])) {

            $this->session->add("old", $_POST);


            $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_STRING);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->msg->error("El correo electrónico no es valido.", BASE_URL . "/forgot-password");
            } else {


                $user = $this->user->getByEmail($email);

                if ($user) {


                    $userId = $user["id"];
                    $username = $user["username"];


                    $payload = array(
                        'userid' => $userId,
                        'exp' => time() + Config::JWT["expiration"]
                    );
                    $key = Config::JWT["secret"];
                    $alg = 'HS256';
                    $token = JWT::encode($payload, $key, $alg);


                    $mailer = new MyMailer(true);                              // Passing `true` enables exceptions
                    $link = BASE_URL . "/reset-password/" . $token;

                    try {
                        $data = [
                            "email" => $email,
                            "username" => $username,
                            "link" => $link
                        ];
                        $html = $this->emailTemplate->render('reset-password.twig', $data);
                        $mailer->addAddress($email);
                        $mailer->isHTML(true);
                        $mailer->Subject = 'Restablecer contraseña';
                        $mailer->Body = $html;
                        $mailer->send();


                        // Quitamos los datos del formulario de la sesión
                        $this->session->remove("old");

                        $this->msg->success("Por favor revise su correo electrónico, recibirá un enlace para restablecer su contraseña.", BASE_URL . "/forgot-password");
                    } catch (Exception $e) {
                        $this->msg->error('No se pudo enviar el mensaje. Error del remitente: ', $mailer->ErrorInfo, BASE_URL . "/forgot-password");
                    }


                } else {
                    $this->msg->error("La dirección de correo electrónico que introdujo no existe", BASE_URL . "/forgot-password");

                }


            }


        } else {

            $this->msg->error("El campo de correo electrónico es requerido", BASE_URL . "/forgot-password");


        }


    }

    public function verifyUser($token)
    {

        $key = Config::JWT["secret"];


        try {
            $tokenToVerify = $token;
            $decodedToken = JWT::decode($tokenToVerify, $key, array('HS256'));
            $userId = $decodedToken->userid;
            $emailVerified = $this->user->checkIfEmailIsVerified($userId);
            if (!$emailVerified) {
                $this->user->verifyEmail($userId);
                $this->msg->success("Su correo electrónico está verificado. Ahora puede iniciar sesión.", BASE_URL . "/login");
            } else {
                $this->msg->info("Su correo electrónico ya ha sido verificado anteriormente. Usted puede iniciar sesión.", BASE_URL . "/login");
            }
        } catch (ExpiredException $e) {
            $myJWT = new MyJWT();
            $payLoad = $myJWT->getPayload($tokenToVerify);
            $user = $this->user->getById($payLoad->userid);
            if ($user) {
                $data = [
                    'titleTag' => "El enlace ha expirado",
                    'flashMessages' => $this->msg->display(null, false),
                    "id" => $payLoad->userid,
                    "email" => $user["email"],
                ];
                echo $this->view->render('account/email-verification-link-expired.twig', $data);
            } else {
                $data = [
                    'titleTag' => "Token no válido",
                    'flashMessages' => $this->msg->display(null, false),
                ];
                echo $this->view->render('account/invalid-token.twig', $data);
            }
        } catch (\Exception $e) {
            $erroMessage = $e->getMessage();
            $data = [
                'titleTag' => "Token no válido",
                'flashMessages' => $this->msg->display(null, false),
                'error' => $erroMessage
            ];
            echo $this->view->render('account/invalid-token.twig', $data);
        }

    }

    public function showRegisterUser()
    {
        $users = $this->user->getAll();
        $this->msg->setMsgCssClass("alert dismissable fade show");
        $data = [
            "titleTag" => "Regístrate",
            "users" => $users,
            'flashMessages' => $this->msg->display(null, false),
        ];
        echo $this->view->render('register.twig', $data);
    }

    public function registerUser()
    {
        // Guardamos los datos del formulario en una variable de sesión llamada "old"
        $this->session->add("old", $_POST);

        $errors = [];

        if (empty($_POST["username"])) {
            array_push($errors, "El campo de nombre de usuario es requerido");
        }

        if (empty($_POST["email"])) {
            array_push($errors, "El campo de correo electrónico es requerido");
        }

        if (empty($_POST["password"])) {
            array_push($errors, "El campo de contraseña es requerido");
        }

        if (empty($_POST["confirmPassword"])) {
            array_push($errors, "El campo para confirmar la contraseña es requerido");
        }

        // Si no hay errores de campos vacíos, entonces aplicamos los filtros de validación
        if (!count($errors) > 0) {
            $postTrimmed = $this->sanitization->getPostVarsTrimmed($_POST);
            // Sanitizamos las variables POST
            $username = filter_var($postTrimmed["username"], FILTER_SANITIZE_STRING);
            $email = filter_var($postTrimmed["email"], FILTER_SANITIZE_STRING);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = filter_var($postTrimmed["password"], FILTER_SANITIZE_STRING);
            $confirmPassword = filter_var($postTrimmed["confirmPassword"], FILTER_SANITIZE_STRING);
            if (!filter_var($username, FILTER_VALIDATE_REGEXP, Validation::USERNAME)) {
                array_push($errors, "El nombre de usuario debe tener caracteres alfanumérico en minúsculas, además de guiones medios y bajos.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "El correo del usuario no es valido.");
            }
            if (strcmp($password, $confirmPassword) !== 0) {
                array_push($errors, "La contraseña del usuario no coincide.");
            }
            $existingEmail = $this->user->getByEmail($email);
            $existingUsername = $this->user->getByUsername($username);
            if ($existingUsername) {
                array_push($errors, "El nombre de usuario ya está registrado. Por favor elige otro.");
            }
            if ($existingEmail) {
                array_push($errors, "La dirección de correo electrónico ya está registrada. Por favor, elige otra.");
            }
            // Si no hay errores, entonces todo esta bien
            if (!count($errors) > 0) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $data = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'verified' => false
                ];
                $userId = $this->user->create($data);
                $userRole = $this->role->getByName("user");
                if ($userRole) {
                    $roleId = $userRole["id"];
                    $this->user->assignRole($userId, $roleId);
                }
                $payload = array(
                    'userid' => $userId,
                    'exp' => time() + Config::JWT["expiration"]
                );
                $key = Config::JWT["secret"];
                $alg = 'HS256';
                $token = JWT::encode($payload, $key, $alg);
                $mailer = new MyMailer(true);                              // Passing `true` enables exceptions
                $link = BASE_URL . "/user-verification/" . $token;
                try {
                    $data = [
                        "username" => $username,
                        "link" => $link
                    ];
                    $html = $this->emailTemplate->render('email-confirmation.twig', $data);
                    $mailer->addAddress($email);
                    $mailer->isHTML(true);
                    $mailer->Subject = 'Activa tu cuenta';
                    $mailer->Body = $html;
                    $mailer->send();
                    // Quitamos los datos del formulario de la sesión
                    $this->session->remove("old");
                    $this->msg->success("Registro completo. Por favor, revisa tu correo electrónico", BASE_URL . "/login");
                } catch (Exception $e) {
                    $this->msg->error('No se pudo enviar el mensaje. Error del remitente: ', $mailer->ErrorInfo, BASE_URL . "/register");
                }
            } else {
                $data = [
                    "messages" => $errors
                ];
                $errorMessages = $this->view->render('misc/flash-messages.twig', $data);
                $this->msg->error($errorMessages, BASE_URL . "/register");
            }
        } else {
            $data = [
                "messages" => $errors
            ];
            $errorMessages = $this->view->render('misc/flash-messages.twig', $data);
            $this->msg->error($errorMessages, BASE_URL . "/register");
        }
    }


}