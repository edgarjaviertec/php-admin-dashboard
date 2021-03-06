<?php

namespace App\Models;

use App\Lib\DB;

class Role extends DB
{
    public function getAll()
    {
        $statement = "SELECT * FROM roles";
        $q = $this->db->query($statement);
        $res = $q->fetchAll();
        return $res;
    }

    public function getById($id)
    {
        $statement = "SELECT * FROM roles WHERE id=:id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->execute();
        $res = $q->fetch();
        return $res;
    }


    public function getByName($name)
    {
        $statement = "SELECT * FROM roles WHERE name=:name";
        $q = $this->db->prepare($statement);
        $q->bindParam(":name", $name, \PDO::PARAM_STR);
        $q->execute();
        $res = $q->fetch();
        return $res;
    }

    public function delete($id)
    {
        $statement = "DELETE FROM roles WHERE id=:id";
        $q = $this->db->prepare($statement);
        $q->bindParam(":id", $id, \PDO::PARAM_INT);
        $q->execute();
        $res = $q->rowCount();
        return $res;
    }

    public function update($id, $perm)
    {
        $statement = "UPDATE roles SET name=:name, display_name=:displayName, description=:description WHERE id=:id";
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
        $statement = "INSERT INTO roles(name, display_name, description) values(:name, :displayName, :description)";
        $q = $this->db->prepare($statement);
        $q->bindParam(":name", $perm["name"], \PDO::PARAM_STR);
        $q->bindParam(":displayName", $perm["displayName"], \PDO::PARAM_STR);
        $q->bindParam(":description", $perm["description"], \PDO::PARAM_STR);
        $q->execute();
        $res = $this->db->lastInsertId();
        return $res;
    }


}
