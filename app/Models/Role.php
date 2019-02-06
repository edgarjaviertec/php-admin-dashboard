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


}