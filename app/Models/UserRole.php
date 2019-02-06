<?

namespace App\Models;
use App\Lib\DB;

class UserRole extends DB
{
    public function getRolesByUser($userId)
    {
        $statement = "SELECT user_roles.role_id,
                      roles.name AS 'role_name'
                      FROM roles
                      INNER JOIN user_roles ON roles.id = user_roles.role_id
                      WHERE user_roles.user_id = :user_id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":user_id", $userId, \PDO::PARAM_INT);
        $q->execute();
        $res = $q->fetchAll();
        return $res;
    }

}