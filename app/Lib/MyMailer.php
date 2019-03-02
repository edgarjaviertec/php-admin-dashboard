<?php

namespace App\Lib;

use App\Config\Config;
use PHPMailer\PHPMailer\PHPMailer;

class MyMailer extends PHPMailer
{
    public function __construct($exceptions = null)
    {
        parent::__construct($exceptions);

        //Server settings
        $this->SMTPDebug = Config::PHP_MAILER["SMTPDebug"];
        $this->isSMTP();
        $this->Host = Config::PHP_MAILER["Host"];
        $this->SMTPAuth = Config::PHP_MAILER["SMTPAuth"];
        $this->Username = Config::PHP_MAILER["Username"];
        $this->Password = Config::PHP_MAILER["Password"];
        $this->SMTPSecure = Config::PHP_MAILER["SMTPSecure"];
        $this->Port = Config::PHP_MAILER["Port"];

        $this->CharSet = 'UTF-8';
        $this->Encoding = 'base64';

        //Recipients
        $this->setFrom(Config::PHP_MAILER["setFromAddress"], Config::PHP_MAILER["setFromName"]);

    }


}