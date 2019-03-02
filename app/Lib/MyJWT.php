<?php
/**
 * Created by PhpStorm.
 * User: edgar
 * Date: 2019-03-01
 * Time: 10:59
 */

namespace App\Lib;

use App\Config\Config;
use Firebase\JWT\JWT;


class MyJWT extends JWT
{

    private $secret;
    private $expiration;

    public function __construct()
    {
        $this->secret = Config::JWT["secret"];
        $this->expiration = time() + Config::JWT["expiration"];
    }


    public function create($userId)
    {

        $payload = array(
            'userid' => $userId,
            'exp' => $this->expiration
        );
        $key = $this->secret;
        $alg = 'HS256';
        $jwt = JWT::encode($payload, $key, $alg);

        return $jwt;


    }


}