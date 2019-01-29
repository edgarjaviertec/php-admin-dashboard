<?


require_once __DIR__ . "/RoleModel.php";


class RolePermissionModel
{

    private $roleModel;


    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }


//    public function getAllRoles()
//    {
//        $query = QB::table('roles')->select(["id", "name"]);
//        $row = $query->get();
//        return $row;
//    }

    public function getPermissionsByRole($id)
    {
        $query = QB::table('roles')
            ->select(QB::raw("permissions.id AS 'permission_id'"))
            ->select(QB::raw("permissions.name AS 'permission_name'"))
            ->join('role_permissions', 'roles.id', '=', 'role_permissions.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('roles.id', $id);
        return $query->get();
    }



    public function getRolesAndPermissions()
    {
        $permissionsByRole = [];
        $roles = $this->roleModel->getAll();
        foreach ($roles as $role) {
            $permissions = $this->getPermissionsByRole($role->id);
            array_push($permissionsByRole, [
                "role_id" => $role->id,
                "role_name" => $role->name,
                "permissions"=> $permissions
            ]);
        }
        return $permissionsByRole;
    }


    public function getPermissionsArrayByRole($id)
    {
        $result = [];
        $permissions = $this->getPermissionsByRole($id);
        foreach ($permissions as $permission) {
            array_push($result, $permission->permission_name);
        }
        return $result;
    }


    public function removeAllPermissions()
    {
        QB::table('role_permissions')->delete();
    }

    public function assignPermissions($roleId, $permissions)
    {
        $insertIds = QB::table('role_permissions')->insert($permissions);
        return $insertIds;
    }
}