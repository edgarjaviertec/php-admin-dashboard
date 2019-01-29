<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/system/libs/session.php";

$session = new Session();
$session->init();
$session->generateCsrfToken();

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/application/controllers/prueba.php';
require __DIR__ . '/application/controllers/LoginController.php';
require __DIR__ . '/application/controllers/backend/HomeController.php';
require __DIR__ . '/application/middleware/csrfTokenMiddleware.php';
require __DIR__ . '/application/controllers/backend/RolePermissionController.php';
require __DIR__ . '/application/controllers/backend/PermissionController.php';

$router = new \Bramus\Router\Router();

$config = array(
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'my_dashboard',
    'username' => 'root',
    'password' => 'root',
);

new \Pixie\Connection('mysql', $config, 'QB');


$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});


$router->get('/', function () {
    echo "la raiz!!!";
});


$router->get('/login', 'loginController@renderLogin');
$router->post('/login', 'loginController@login');

$router->before('GET', '/logout', 'csrfTokenMiddleware@verifyCsrfToken');
$router->get('/logout', 'loginController@logout');

$router->mount('/backend', function() use ($router) {
    $router->get('/', 'homeController@index');



    $router->post('/permissions/destroy', 'PermissionController@destroy');
    $router->post('/permissions/update', 'PermissionController@update');
    $router->post('/permissions/store', 'PermissionController@store');
    $router->get('/permissions', 'PermissionController@index');
    $router->get('/permissions/(\d+)/edit', 'PermissionController@edit');
    $router->get('/permissions/create', 'PermissionController@create');




    $router->get('/assign-permissions', 'RolePermissionController@index');
    $router->post('/assign-permissions', 'RolePermissionController@assignPermissions');
});






$router->run();