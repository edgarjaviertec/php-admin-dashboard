<?php


namespace App\Controllers\Backend;

use App\Lib\TwigHelpers;
use App\Lib\Session;
use App\Models\Role;
use Plasticbrain\FlashMessages\FlashMessages;

class RoleController extends TwigHelpers
{
    private $session;
    private $role;

    public function __construct()
    {
        parent::__construct();
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
        $roles = $this->role->getAll();
        $data = [
            "titleTag" => "Roles",
            "roles" => $roles,
            'flashMessages' => $this->msg->display(null, false),
        ];
        echo $this->view->render('backend/list-roles.twig', $data);
    }

    public function displayEditView($id)
    {
        $permission = $this->role->getById($id);
        if (!empty($permission)) {
            $data = [
                "titleTag" => "Edtar rol",
                "permission" => $permission,
                'flashMessages' => $this->msg->display(null, false)
            ];
            echo $this->view->render('backend/edit-role.twig', $data);
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            echo '404, route not found!';
        }

    }


    public function updateRole()
    {
        if (isset($_POST['id'])) {
            $permissionId = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'displayName' => $_POST['displayName'],
                'description' => $_POST['description']
            ];
            $this->role->update($permissionId, $data);
            $this->msg->success("Rol actualizado exitosamente", '/backend/roles');
        }
    }


    public function deleteRole()
    {
        if (isset($_POST['id'])) {
            $roleId = $_POST['id'];
            $this->role->delete($roleId);
            $this->msg->success("Se ha eliminado el rol exitosamente", '/backend/roles');
        }
    }


    public function displayNewView()
    {
        $data = [
            "titleTag" => "Nuevo rol",
            'flashMessages' => $this->msg->display(null, false)
        ];
        echo $this->view->render('backend/new-role.twig', $data);
    }


    public function createRole()
    {
        if (isset($_POST)) {
            $data = [
                'name' => $_POST['name'],
                'displayName' => $_POST['displayName'],
                'description' => $_POST['description']
            ];
            $insertedRows = $this->role->create($data);
            $this->msg->success("Se creó un nuevo rol con el Id: " . $insertedRows, '/backend/roles');
        }
    }


}

