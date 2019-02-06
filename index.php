<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('App\\', __DIR__);

require_once __DIR__ . '/app/Controllers/LoginController.php';
require_once __DIR__ . '/app/Controllers/Backend/HomeController.php';
require_once __DIR__ . '/app/Controllers/Backend/RolePermissionController.php';
require_once __DIR__ . '/app/Controllers/Backend/PermissionController.php';
require_once __DIR__ . '/app/Controllers/Backend/UserController.php';
require_once __DIR__ . '/app/Middleware/csrfTokenMiddleware.php';
require_once __DIR__ . "/app/Lib/Session.php";
require_once __DIR__ . "/app/Lib/CSRF.php";

use App\Lib\Session;
use App\Lib\CSRF;


$session = new Session();
$csrf = new CSRF();

$session->init();
$csrf->generateToken();

$router = new \Bramus\Router\Router();



$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});


$router->get('/', function () {
    echo "la raiz!!!";
});


$router->get('/login', '\App\Controllers\LoginController@renderLogin');
$router->post('/login', '\App\Controllers\LoginController@login');

$router->before('GET', '/logout', '\App\Middleware\CsrfTokenMiddleware@verifyCsrfToken');
$router->get('/logout', '\App\Controllers\LoginController@logout');

$router->mount('/backend', function () use ($router) {
    $router->get('/', '\App\Controllers\Backend\HomeController@index');

    $router->post('/permissions/destroy', '\App\Controllers\Backend\PermissionController@deletePermission');
    $router->post('/permissions/update', '\App\Controllers\Backend\PermissionController@updatePermission');
    $router->post('/permissions/store', '\App\Controllers\Backend\PermissionController@createPermission');
    $router->get('/permissions', '\App\Controllers\Backend\PermissionController@index');
    $router->get('/permissions/(\d+)/edit', '\App\Controllers\Backend\PermissionController@displayEditView');
    $router->get('/permissions/create', '\App\Controllers\Backend\PermissionController@displayNewView');


    // Rutas para la sección "Usuarios"
    $router->get('/users', '\App\Controllers\Backend\UserController@index');
    $router->get('/users/new', '\App\Controllers\Backend\UserController@displayNewView');
    $router->post('/users/create', '\App\Controllers\Backend\UserController@createUser');
    $router->get('/users/(\d+)/edit', '\App\Controllers\Backend\UserController@displayEditView');
    $router->post('/users/update', '\App\Controllers\Backend\UserController@updateUser');
    $router->post('/users/delete', '\App\Controllers\Backend\UserController@deleteUser');


    $router->get('/users/(\d+)/change-password', '\App\Controllers\Backend\UserController@displayChangePasswordView');
    $router->post('/users/change-password', '\App\Controllers\Backend\UserController@changeUserPassword');


    $router->get('/assign-permissions', '\App\Controllers\Backend\RolePermissionController@index');
    $router->post('/assign-permissions', '\App\Controllers\Backend\RolePermissionController@assignPermissions');
});


$router->run();