<?php

require_once __DIR__ . '/../config/config.php';

abstract class TwigBase
{
    public $view;
    public function __construct()
    {
        $this->view = new Twig_Environment(
            new Twig_Loader_Filesystem(AppConfig::STATIC_PATHS['viewDir']), [
            'auto_reload'=>true,
            'debug'=>true,
            'cache'=>false
        ]);
    }
}