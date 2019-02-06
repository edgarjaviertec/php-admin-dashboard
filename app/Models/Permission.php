<?php

namespace App\Models;

use App\Lib\DB;

class Permission extends DB
{
    public function getAll()
    {
        $statement = "SELECT * FROM permissions";
        $q = $this->db->query($statement);
        $res = $q->fetchAll();
        return $res;
    }

    public function getById($id)
    {
        $statement = "SELECT * FROM permissions WHERE id=:id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->execute();
        $res = $q->fetch();
        return $res;
    }

    public function delete($id)
    {
        $statement = "DELETE FROM permissions WHERE id=:id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->execute();
        $res = $q->rowCount();
        return $res;
    }

    public function update($id, $perm)
    {
        $statement = "UPDATE permissions SET name=:name, display_name=:displayName, description=:description WHERE id=:id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->bindParam(":name", $perm["name"], \PDO::PARAM_STR);
        $q->bindParam(":displayName", $perm["displayName"], \PDO::PARAM_STR);
        $q->bindParam(":description", $perm["description"], \PDO::PARAM_STR);
        $q->execute();
        $res = $q->rowCount();
        return $res;
    }

    public function create($perm)
    {
        $statement = "INSERT INTO permissions(name, display_name, description) values(:name, :displayName, :description)";
        $q = $this->db->prepare($statement);
        $q->bindParam(":name", $perm["name"], \PDO::PARAM_STR);
        $q->bindParam(":displayName", $perm["displayName"], \PDO::PARAM_STR);
        $q->bindParam(":description", $perm["description"], \PDO::PARAM_STR);
        $q->execute();
        $res = $this->db->lastInsertId();
        return $res;
    }

}