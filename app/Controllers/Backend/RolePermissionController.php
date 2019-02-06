<?php


namespace App\Controllers\Backend;
use App\Lib\TwigHelpers;

use App\Lib\Session;
use App\Models\Role;
use App\Models\UserRole;
use Plasticbrain\FlashMessages\FlashMessages;
use App\Models\RolePermission;
use App\Models\Permission;

class RolePermissionController extends TwigHelpers
{
    private $session;
    private $role;
    private $rolePermissionModel;
    private $permissionModel;

    public function __construct()
    {
        parent::__construct();

        // Aquí debes inicializar los modelos que vas a usar
        $this->role = new Role();
        $this->rolePermissionModel = new RolePermission();
        $this->permissionModel = new Permission();

        $this->session = new Session();
        $this->msg = new FlashMessages;

        // Si no hay ninguna sesión o la variable de sesión "logged_in_user"
        // no esta definida, entonces redirigimos al usuario a la vista del "login"
        if ($this->session->getStatus() === 1 || empty($this->session->get('logged_in_user'))) {
            header('Location: /login');
            exit();
        }
    }

    function index()
    {
        $roles = $this->role->getAll();
        $permissions = $this->permissionModel->getAll();
        $permissionsByRole = $this->rolePermissionModel->getRolesAndPermissions();

        $data = [
            "titleTag" => "Asignar permisos",
            "roles" => $roles,
            "permissions" => $permissions,
            "rolesAndPermissions" => $permissionsByRole,
            'flashMessages' => $this->msg->display(null, false)
        ];
        echo $this->view->render('Backend/assign-permissions.twig', $data);
    }

    function assignPermissions()
    {
        $insertedIds = 0;
        $this->rolePermissionModel->removeAllPermissions();
        if (isset($_POST['permissions'])) {
            $permissionsByRole = $_POST['permissions'];
            foreach ($permissionsByRole as $key => $role) {
                $valuesToInsert = [];
                foreach ($role as $permission) {
                    array_push(
                        $valuesToInsert,
                        [
                            'role_id' => $key,
                            'permission_id' => $permission
                        ]
                    );
                }
                if (count($valuesToInsert) > 0) {
                    $result = $this->rolePermissionModel->assignPermissions($valuesToInsert);
                    $insertedIds = $insertedIds + count($result);

                }
            }
            $this->msg->success("Se actualizaron " . $insertedIds . " elementos", '/backend/assign-permissions');
        }
        $this->msg->success("Se han actualizado los permisos", '/backend/assign-permissions');

    }
}