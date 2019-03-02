<?php


namespace App\Controllers;


use App\Config\Config;
use App\Lib\TwigHelpers;
use App\Models\User;
use App\Models\Role;
use App\Lib\Sanitization;
use App\Lib\Validation;
use App\Lib\Session;


use Firebase\JWT\JWT;
use App\Lib\MyMailer;
use Plasticbrain\FlashMessages\FlashMessages;


class RegisterController extends TwigHelpers
{

    private $user;
    private $role;
    private $sanitization;
    private $msg;
    private $session;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->role = new Role();
        $this->msg = new FlashMessages;
        $this->sanitization = new Sanitization();
        $this->session = new Session();

    }

    public function index()
    {
        $users = $this->user->getAll();
        $this->msg->setMsgCssClass("alert dismissable fade show");
        $data = [
            "titleTag" => "Usuarios",
            "users" => $users,
            'flashMessages' => $this->msg->display(null, false),
        ];
        echo $this->view->render('register.twig', $data);
    }


    public function createUser()
    {


        if (isset($_POST)) {

            // Guardamos los datos del formulario en una variable de sesión llamada "old"
            $this->session->add("old", $_POST );


            $errors = [];
            $postTrimmed = $this->sanitization->getPostVarsTrimmed($_POST);

            // Sanitizamos las variables POST
            $username = filter_var($postTrimmed["username"], FILTER_SANITIZE_STRING);
            $email = filter_var($postTrimmed["email"], FILTER_SANITIZE_STRING);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = filter_var($postTrimmed["password"], FILTER_SANITIZE_STRING);
            $confirmPassword = filter_var($postTrimmed["confirmPassword"], FILTER_SANITIZE_STRING);
            // Validar si los campos requeridos no están vacíos
            $requiredFields = ["username", "email", "password", "confirmPassword"];
            foreach ($postTrimmed as $key => $val) {
                if (in_array($key, $requiredFields) && empty($val)) {
                    array_push($errors, "El campo [" . $key . "] es requerido");
                }
            }


            // Si no hay errores de campos vacíos, entonces aplicamos los filtros de validación
            if (!count($errors) > 0) {
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


                    if($userRole){
                        $roleId = $userRole["id"];
                        $this->user->assignRole($userId, $roleId);
                    }

                    $payload = array(
                        'userid' => $userId,
                        'exp' => time() +  Config::JWT["expiration"]
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
                        $html = $this->emailTemplate->render('user-confirmation.twig', $data);
                        $mailer->addAddress($email);
                        $mailer->isHTML(true);
                        $mailer->Subject = 'Activa tu cuenta';
                        $mailer->Body = $html;
                        $mailer->send();


                        // Quitamos los datos del formulario de la sesión
                        $this->session->remove("old");

                        $this->msg->success("Registro completo. Por favor, revisa tu correo electrónico",  BASE_URL . "login");
                    } catch (Exception $e) {
                        $this->msg->error('No se pudo enviar el mensaje. Error del remitente: ', $mailer->ErrorInfo,  BASE_URL . "register");
                    }

                } else {
                    $data = [
                        "messages" => $errors
                    ];
                    $errorMessages = $this->view->render('misc/flash-messages.twig', $data);
                    $this->msg->error($errorMessages, BASE_URL . "register");
                }
            } else {
                $data = [
                    "messages" => $errors
                ];
                $errorMessages = $this->view->render('misc/flash-messages.twig', $data);
                $this->msg->error($errorMessages, BASE_URL . "register");
            }
        }
    }


    public function changeUserPassword()
    {
        if (isset($_POST['id'])) {
            $userId = $_POST['id'];
            $newPassword = $_POST['newPassword'];
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $confirmPassword = $_POST['confirmPassword'];
            $this->user->changePassword($userId, $hashedPassword);
            $this->msg->success("Se cambio la contraseña con éxito", '/backend/users');
        }
    }

}

