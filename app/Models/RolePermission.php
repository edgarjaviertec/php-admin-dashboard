<?

namespace App\Models;
use App\Lib\DB;

class RolePermission extends DB
{
    private $role;

    public function __construct()
    {
        parent::__construct();
        $this->role = new Role();
    }

    public function getPermissionsByRole($id)
    {
        $statement = "SELECT 
                      permissions.id AS 'permission_id',
                      permissions.name AS 'permission_name'
                      FROM roles
                      INNER JOIN roles_permissions
                      ON roles.id = roles_permissions.role_id
                      INNER JOIN permissions
                      ON roles_permissions.permission_id = permissions.id
                      WHERE roles.id = :id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->execute();
        $res = $q->fetchAll(\PDO::FETCH_CLASS);
        // Convierto el resultado a un arreglo
        $res = json_decode(json_encode($res), true);
        return $res;

    }

    public function getRolesAndPermissions()
    {
        $permissionsByRole = [];
        $roles = $this->role->getAll();
        foreach ($roles as $role) {
            $permissions = $this->getPermissionsByRole($role["id"]);
            array_push($permissionsByRole, [
                "role_id" => $role["id"],
                "role_name" => $role["name"],
                "permissions" => $permissions
            ]);
        }
        return $permissionsByRole;
    }

    public function removeAllPermissions()
    {
        $q = $this->db->prepare("DELETE FROM roles_permissions");
        $q->execute();
        $res = $q->rowCount();
        return $res;
    }

    public function assignPermissions($permissions)
    {
        $insertedIds = [];
        if (count($permissions) > 0) {
            foreach ($permissions as $permission) {
                $statement = "INSERT INTO roles_permissions(role_id, permission_id) VALUES(:role_id, :permission_id)";
                $q = $this->db->prepare($statement);
                $q->bindParam(":role_id", $permission["role_id"], \PDO::PARAM_INT);
                $q->bindParam("permission_id", $permission["permission_id"], \PDO::PARAM_INT);
                $q->execute();
                array_push($insertedIds, $this->db->lastInsertId());
            }
            return $insertedIds;
        }
    }

}