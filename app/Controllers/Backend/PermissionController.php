<?php


namespace App\Controllers\Backend;

use App\Lib\TwigHelpers;
use App\Models\Permission;
use App\Lib\Session;
use Plasticbrain\FlashMessages\FlashMessages;

class PermissionController extends TwigHelpers
{
    private $session;
    private $permissionModel;

    public function __construct()
    {
        parent::__construct();
        $this->permissionModel = new Permission();
        $this->session = new Session();
        $this->msg = new FlashMessages;
        if ($this->session->getStatus() === 1 || empty($this->session->get('logged_in_user'))) {
            header('Location: /login');
            exit();
        }
    }

    public function index()
    {
        $permissions = $this->permissionModel->getAll();
        $data = [
            "titleTag" => "Permisos",
            "permissions" => $permissions,
            'flashMessages' => $this->msg->display(null, false),
            'permisos' => [
                "pemiso1", "permiso2", "permiso3"
            ]
        ];

        echo $this->view->render('Backend/list-permissions.twig', $data);
    }

    public function displayEditView($id)
    {
        $permission = $this->permissionModel->getById($id);
        if (!empty($permission)) {
            $data = [
                "titleTag" => "Edtar permiso",
                "permission" => $permission,
                'flashMessages' => $this->msg->display(null, false)
            ];
            echo $this->view->render('Backend/edit-permission.twig', $data);
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            echo '404, route not found!';
        }

    }

    public function deletePermission()
    {
        if (isset($_POST['id'])) {
            $permissionId = $_POST['id'];
            $deletedRows = $this->permissionModel->delete($permissionId);
            $this->msg->success("Permisos eliminados exitosamente: " . $deletedRows, '/backend/permissions');
        }
    }

    public function updatePermission()
    {
        if (isset($_POST['id'])) {
            $permissionId = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'displayName' => $_POST['displayName'],
                'description' => $_POST['description']
            ];
            $updatedRows = $this->permissionModel->update($permissionId, $data);
            $this->msg->success("Permisos actualizados: " . $updatedRows, '/backend/permissions');
        }
    }

    public function createPermission()
    {
        if (isset($_POST)) {
            $data = [
                'name' => $_POST['name'],
                'displayName' => $_POST['displayName'],
                'description' => $_POST['description']
            ];
            $insertedRows = $this->permissionModel->create($data);
            $this->msg->success("Se creo el permiso con el id: " . $insertedRows, '/backend/permissions');
        }
    }

    public function displayNewView()
    {
        $data = [
            "titleTag" => "Nuevo permiso",
            'flashMessages' => $this->msg->display(null, false)
        ];
        echo $this->view->render('Backend/new-permission.twig', $data);
    }

}

