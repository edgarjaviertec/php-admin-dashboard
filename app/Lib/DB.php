<?php

namespace App\Lib;

use App\Config\Config;

class DB
{

    protected $db;

    public function __construct()
    {
        $this->db = $this->connection();
    }

    public function connection()
    {
        $driver = Config::DB_CONFIG['driver'];
        $host = Config::DB_CONFIG['host'];
        $database = Config::DB_CONFIG['database'];
        $user = Config::DB_CONFIG['username'];
        $pass = Config::DB_CONFIG['password'];
        $charset = Config::DB_CONFIG['charset'];
        $dsn = "{$driver}:host={$host};dbname={$database};charset={$charset}";
        $con = new \PDO($dsn, $user, $pass);
        return $con;
    }

}

