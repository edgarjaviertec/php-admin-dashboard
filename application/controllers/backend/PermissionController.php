<?php

require_once __DIR__ . '/../TwigBase.php';
require_once __DIR__ . '/../../../system/libs/session.php';
require_once __DIR__ . '/../../libs/TwigTests.php';
require_once __DIR__ . '/../../models/PermissionModel.php';



use Plasticbrain\FlashMessages\FlashMessages;

class PermissionController extends TwigBase
{
    private $session;
    private $permissionModel;

    public function __construct()
    {
        parent::__construct();
        $this->permissionModel = new PermissionModel();
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
            "titleTag" => "hooola",
            "permissions" => $permissions,
            'flashMessages' => $this->msg->display(null, false),
            'permisos' => [
                "pemiso1", "permiso2", "permiso3"
            ]
        ];

        $tests = new TwigTests();
        $this->view->addTest($tests->userPermissionsTest());
        echo $this->view->render('backend/list-permissions.twig', $data);
    }


    public function edit($id)

    {
        $permission = $this->permissionModel->getById($id);
        if (!empty($permission)) {
            $data = [
                "titleTag" => "hooola",
                "permission" => $permission,
                'flashMessages' => $this->msg->display(null, false)
            ];
            echo $this->view->render('backend/edit-permission.twig', $data);
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            echo '404, route not found!';
        }

    }


    public function destroy()
    {
        if (isset($_POST['id'])) {
            $permissionId = $_POST['id'];
            $this->permissionModel->destroy($permissionId);
            $this->msg->success("Permiso eliminado exitosamente", '/backend/permissions');
        }
    }


    public function update()
    {
        if (isset($_POST['id'])) {
            $permissionId = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'displayName' => $_POST['displayName'],
                'description' => $_POST['description']
            ];
            $this->permissionModel->update($permissionId, $data);
            $this->msg->success("Se actualizo el permiso correctamente", '/backend/permissions');
        }


    }


    public function store()
    {
        if (isset($_POST)) {
            $data = [
                'name' => $_POST['name'],
                'displayName' => $_POST['displayName'],
                'description' => $_POST['description']
            ];
            $this->permissionModel->store($data);
            $this->msg->success("Se creo el permiso exitosamente", '/backend/permissions');
        }


    }


    public function create()
    {
        $data = [
            "titleTag" => "hooola",
            'flashMessages' => $this->msg->display(null, false)
        ];
        echo $this->view->render('backend/create-permission.twig', $data);
    }


}