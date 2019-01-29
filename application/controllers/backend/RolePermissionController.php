<?php

require_once __DIR__ . '/../TwigBase.php';
require_once __DIR__ . '/../../../system/libs/session.php';
require_once __DIR__ . '/../../models/RolePermissionModel.php';
require_once __DIR__ . '/../../models/RoleModel.php';
require_once __DIR__ . '/../../models/PermissionModel.php';

use Plasticbrain\FlashMessages\FlashMessages;

class RolePermissionController extends TwigBase
{
    private $session;
    private $roleModel;
    private $rolePermissionModel;
    private $permissionModel;

    public function __construct()
    {
        parent::__construct();
        $this->roleModel = new RoleModel();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->permissionModel = new PermissionModel();
        $this->session = new Session();
        $this->msg = new FlashMessages;
        if ($this->session->getStatus() === 1 || empty($this->session->get('logged_in_user'))) {
            header('Location: /login');
            exit();
        }
    }

    function index()
    {
        $roles = $this->roleModel->getAll();
        $permissions = $this->permissionModel->getAll();
        $permissionsByRole = $this->rolePermissionModel->getRolesAndPermissions();
        $data = [
            "titleTag" => "hooola",
            "roles" => $roles,
            "permissions" => $permissions,
            "rolesAndPermissions" => $permissionsByRole,
            'flashMessages' => $this->msg->display(null, false)
        ];
        echo $this->view->render('backend/assign-permissions.twig', $data);
    }

    function assignPermissions()
    {
        $insertedIds = 0;
        $this->rolePermissionModel->removeAllPermissions();
        if (isset($_POST['permissions'])) {
            $permissionsByRole = $_POST['permissions'];
            foreach ($permissionsByRole as $key => $role) {
                $valueToInsert = [];
                foreach ($role as $permission) {
                    array_push(
                        $valueToInsert,
                        [
                            'role_id' => $key,
                            'permission_id' => $permission
                        ]
                    );
                }
                if (count($valueToInsert) > 0) {

                    $result = $this->rolePermissionModel->assignPermissions($key, $valueToInsert);
                    $insertedIds = $insertedIds + count($result);

                }
            }
            $this->msg->success("Se actualizaron " . $insertedIds . " elementos", '/backend/assign-permissions');
        }
        $this->msg->success("Se han actualizado los permisos", '/backend/assign-permissions');

    }
}