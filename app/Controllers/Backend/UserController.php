<?php


namespace App\Controllers\Backend;

use App\Lib\TwigHelpers;

use App\Models\User;
use App\Models\Role;
use App\Lib\Session;

use Plasticbrain\FlashMessages\FlashMessages;

class UserController extends TwigHelpers
{

    private $session;
    private $user;
    private $role;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->role = new Role();
        $this->session = new Session();
        $this->msg = new FlashMessages;
        if ($this->session->getStatus() === 1 || empty($this->session->get('logged_in_user'))) {
            header('Location: /login');
            exit();
        }
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
        echo $this->view->render('Backend/list-users.twig', $data);
    }

    public function displayNewView()
    {
        $roles = $this->role->getAll();
        $data = [
            "titleTag" => "Nuevo usuario",
            "roles" => $roles
        ];
        echo $this->view->render('Backend/new-user.twig', $data);
    }

    public function createUser()
    {
        if (isset($_POST)) {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $hashedPassword,
                'accountStatus' => $_POST['accountStatus']
            ];
            $roleId = $_POST["role"];
            $userId = $this->user->create($data);
            $this->user->assignRole($userId, $roleId);
            $this->msg->success("Se creo el usuario con el id: " . $userId, '/backend/users');
        }
    }

    public function displayEditView($id)
    {
        $user = $this->user->getById($id);
        $roles = $this->role->getAll();
        if (!empty($user)) {
            $data = [
                "titleTag" => "Editar usuario",
                "user" => $user,
                "roles" => $roles,
            ];
            echo $this->view->render('backend/edit-user.twig', $data);
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            echo '404, route not found!';
        }
    }

    public function updateUser()
    {
        if (isset($_POST['id'])) {
            $userId = $_POST['id'];
            $roleId = $_POST['role'];
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'accountStatus' => $_POST['accountStatus']
            ];
            $this->user->removeRole($userId);
            $this->user->assignRole($userId, $roleId);
            $this->user->update($userId, $data);
            $this->msg->success("Usuario actualizado", '/backend/users');
        }
    }

    public function deleteUser()
    {
        if (isset($_POST['id'])) {
            $permissionId = $_POST['id'];
            $deletedRows = $this->user->delete($permissionId);
            $this->msg->success("Permisos eliminados exitosamente: " . $deletedRows, '/backend/users');
        }
    }

    public function displayChangePasswordView($id)
    {
        $user = $this->user->getById($id);
        if (!empty($user)) {
            $data = [
                "titleTag" => "Cambiar contraseña",
                "user" => $user
            ];
            echo $this->view->render('backend/change-user-passssword.twig', $data);
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            echo '404, route not found!';
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

