<?php

namespace App\Models;

use App\Lib\DB;

class User extends DB
{
    public function getAll()
    {
        $statement = "SELECT users.id, users.username, users.email, users.verified, roles.id AS role_id, roles.display_name AS role_display_name
                      FROM users
                      LEFT JOIN users_roles ON users.id = users_roles.user_id
                      LEFT JOIN roles ON users_roles.role_id = roles.id";
        $q = $this->db->query($statement);
        $res = $q->fetchAll();
        return $res;
    }

    public function getById($id)
    {
        $statement = "SELECT users.id, users.username, users.email, users.verified, roles.id AS role_id, roles.display_name AS role_display_name
                      FROM users
                      LEFT JOIN users_roles ON users.id = users_roles.user_id
                      LEFT JOIN roles ON users_roles.role_id = roles.id
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



    public function getByUsername($username)
    {
        $statement = "SELECT * FROM users WHERE username = :username";
        $q = $this->db->prepare($statement);
        $q->bindParam(":username", $username, \PDO::PARAM_STR);
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
        $statement = "UPDATE users SET username=:username, email=:email, verified=:verified WHERE id=:id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->bindParam(":username", $user["username"], \PDO::PARAM_STR);
        $q->bindParam(":email", $user["email"], \PDO::PARAM_STR);
        $q->bindParam(":verified", $user["verified"], \PDO::PARAM_BOOL);
        $q->execute();
        $res = $q->rowCount();
        return $res;
    }

    public function create($user)
    {
        $statement = "INSERT INTO users(username, email, password, verified) values(:username, :email, :password, :verified);";
        $q = $this->db->prepare($statement);
        $q->bindParam(":username", $user["username"], \PDO::PARAM_STR);
        $q->bindParam(":email", $user["email"], \PDO::PARAM_STR);
        $q->bindParam(":password", $user["password"], \PDO::PARAM_STR);
        $q->bindParam(":verified", $user["verified"], \PDO::PARAM_BOOL);
        $q->execute();
        $res = $this->db->lastInsertId();
        return $res;
    }

    public function assignRole($userId, $roleId)
    {
        $statement = "INSERT INTO users_roles(user_id, role_id) VALUES(:userId,:roleId);";
        $q = $this->db->prepare($statement);
        $q->bindParam(":userId", $userId, \PDO::PARAM_INT);
        $q->bindParam(":roleId", $roleId, \PDO::PARAM_INT);
        $q->execute();
        $res = $this->db->lastInsertId();
        return $res;
    }

    public function removeRole($userId)
    {
        $statement = "DELETE FROM users_roles WHERE users_roles.user_id = :userId";
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