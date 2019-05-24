<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluimos el autoload de clases con Composer para que las librerías se puedan cargar automáticamente, por demanda según se vayan usando
$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('App\\', __DIR__);

// Iniciamos una sesión y generamos el CSRF token
require_once __DIR__ . "/app/Lib/Session.php";
require_once __DIR__ . "/app/Lib/CSRF.php";
$session = new App\Lib\Session();
$csrf = new App\Lib\CSRF();
$session->init();
$csrf->generateToken();

// Se establece la URL base como una constante para poder usarlo donde sea
$base_url = ( (isset($_SERVER['HTTPS']) ) ? "https" : "http");
$base_url .= "://".$_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$base_url = rtrim( $base_url,'/');
define('BASE_URL', $base_url);

// Aquí se cargan las rutas de la aplicación
$router = new \Bramus\Router\Router();
$backendRoutes = new App\Routes\BackendRoutes();
$frontendRoutes = new App\Routes\FrontendRoutes();
$frontendRoutes->loadRoutes($router);
$backendRoutes->loadRoutes($router);
$router->run();
