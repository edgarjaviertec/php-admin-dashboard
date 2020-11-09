<?php

namespace App\Lib;

class DB
{

    protected $db;

    public function __construct()
    {
        $this->db = $this->connection();
    }

    public function connection()
    {
        $driver = config('db_driver');
        $host = config('db_host');
        $database = config('db_database');
        $user = config('db_username');
        $pass = config('db_password');
        $charset = config('db_charset');
        $dsn = "{$driver}:host={$host};dbname={$database};charset={$charset}";
        $con = new \PDO($dsn, $user, $pass);
        return $con;
    }

}

