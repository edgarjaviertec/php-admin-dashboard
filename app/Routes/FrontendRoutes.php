<?php

namespace App\Routes;

class FrontendRoutes
{
    public function loadRoutes($router)
    {
        // Rutas para el frontend
        $ns = "\App\Controllers\\";
        $router->set404(function () {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            echo '404, route not found!';
        });
        $router->get('/', function () {
            echo "la raiz!!!";
        });
        $router->get('/login', $ns . 'AccountController@renderLogin');
        $router->post('/login', $ns . 'AccountController@login');


        $router->get('/user-verification/([\w._-]+)', $ns . 'AccountController@verifyUser');



        $router->get('/register', $ns . 'RegisterController@index');
        $router->post('/register', $ns . 'RegisterController@createUser');


        $router->before('GET', '/logout', '\App\Middleware\CsrfTokenMiddleware@verifyCsrfToken');
        $router->get('/logout', $ns . 'AccountController@logout');
    }
}
