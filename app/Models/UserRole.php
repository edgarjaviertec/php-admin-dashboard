<?php

namespace App\Models;
use App\Lib\DB;

class UserRole extends DB
{
    public function getRolesByUser($userId)
    {
        $statement = "SELECT users_roles.role_id,
                      roles.name AS 'role_name'
                      FROM roles
                      INNER JOIN users_roles ON roles.id = users_roles.role_id
                      WHERE users_roles.user_id = :user_id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":user_id", $userId, \PDO::PARAM_INT);
        $q->execute();
        $res = $q->fetchAll();
        return $res;
    }

}