<?php

namespace App\Lib;

use PHPMailer\PHPMailer\PHPMailer;

class MyMailer extends PHPMailer
{
    public function __construct($exceptions = null)
    {
        parent::__construct($exceptions);
        //Server settings
        $this->SMTPDebug = config('mail_debug');
        $this->isSMTP();
        $this->Host = config('mail_host');
        $this->SMTPAuth = config('mail_auth');
        $this->Username = config('mail_username');
        $this->Password = config('mail_password');
        $this->SMTPSecure = config('mail_encryption');
        $this->Port = config('mail_port');
        $this->CharSet = 'UTF-8';
        $this->Encoding = 'base64';
        //Recipients
        $this->setFrom(config('mail_from_address'), config('mail_from_name'));
    }
}