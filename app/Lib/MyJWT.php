<?php

namespace App\Lib;

use Firebase\JWT\JWT;


class MyJWT extends JWT
{

    public function getPayload($jwt)
    {
        $tks = explode('.', $jwt);
        $bodyb64 = $tks[1];
        $payload = static::jsonDecode(static::urlsafeB64Decode($bodyb64));
        return $payload;
    }


}