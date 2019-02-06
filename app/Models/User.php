<?php

namespace App\Models;

use App\Lib\DB;

class User extends DB
{
    public function getAll()
    {
        $statement = "SELECT users.id, users.username, users.email, users.account_status, roles.id AS role_id, roles.display_name AS role_display_name
                      FROM users
                      LEFT JOIN user_roles ON users.id = user_roles.user_id
                      LEFT JOIN roles ON user_roles.role_id = roles.id";
        $q = $this->db->query($statement);
        $res = $q->fetchAll();
        return $res;
    }

    public function getById($id)
    {
        $statement = "SELECT users.id, users.username, users.email, users.account_status, roles.id AS role_id, roles.display_name AS role_display_name
                      FROM users
                      INNER JOIN user_roles ON users.id = user_roles.user_id
                      INNER JOIN roles ON user_roles.role_id = roles.id
                      WHERE users.id = :id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->execute();
        $res = $q->fetch();
        return $res;
    }

    public function getByEmail($email)
    {
        $statement = "SELECT * FROM users WHERE email = :email";
        $q = $this->db->prepare($statement);
        $q->bindParam(":email", $email, \PDO::PARAM_STR);
        $q->execute();
        $res = $q->fetch();
        return $res;
    }

    public function delete($id)
    {
        $statement = "DELETE FROM users WHERE id=:id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->execute();
        $res = $q->rowCount();
        return $res;
    }

    public function update($id, $user)
    {
        $statement = "UPDATE users SET username=:username, email=:email, account_status=:accountStatus WHERE id=:id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->bindParam(":username", $user["username"], \PDO::PARAM_STR);
        $q->bindParam(":email", $user["email"], \PDO::PARAM_STR);
        $q->bindParam(":accountStatus", $user["accountStatus"], \PDO::PARAM_BOOL);
        $q->execute();
        $res = $q->rowCount();
        return $res;
    }

    public function create($user)
    {
        $statement = "INSERT INTO users(username, email, password, account_status) values(:username, :email, :password, :accountStatus);";
        $q = $this->db->prepare($statement);
        $q->bindParam(":username", $user["username"], \PDO::PARAM_STR);
        $q->bindParam(":email", $user["email"], \PDO::PARAM_STR);
        $q->bindParam(":password", $user["password"], \PDO::PARAM_STR);
        $q->bindParam(":accountStatus", $user["accountStatus"], \PDO::PARAM_BOOL);
        $q->execute();
        $res = $this->db->lastInsertId();
        return $res;
    }

    public function assignRole($userId, $roleId)
    {
        $statement = "INSERT INTO user_roles(user_id, role_id) VALUES(:userId,:roleId);";
        $q = $this->db->prepare($statement);
        $q->bindParam(":userId", $userId, \PDO::PARAM_INT);
        $q->bindParam(":roleId", $roleId, \PDO::PARAM_INT);
        $q->execute();
        $res = $this->db->lastInsertId();
        return $res;
    }

    public function removeRole($userId)
    {
        $statement = "DELETE FROM user_roles WHERE user_roles.user_id = :userId";
        $q = $this->db->prepare($statement);
        $q->bindParam(":userId", $userId, \PDO::PARAM_INT);
        $q->execute();
        $res = $this->db->lastInsertId();
        return $res;
    }

    public function changePassword($id, $newPassword)
    {
        $statement = "UPDATE users SET password=:newPassword WHERE id=:id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->bindParam(":newPassword", $newPassword, \PDO::PARAM_STR);
        $q->execute();
        $res = $q->rowCount();
        return $res;
    }

}