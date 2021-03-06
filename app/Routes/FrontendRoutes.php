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
        $router->get('/', $ns . 'HomeController@index');

        $router->get('/login', $ns . 'AccountController@showLogin');
        $router->post('/login', $ns . 'AccountController@login');

        $router->get('/forgot-password', $ns . 'AccountController@showForgotPassword');
        $router->post('/forgot-password', $ns . 'AccountController@forgotPassword');

        $router->get('/reset-password/([\w._-]+)', $ns . 'AccountController@showResetPassword');
        $router->post('/reset-password/([\w._-]+)', $ns . 'AccountController@resetPassword');

        $router->get('/user-verification/([\w._-]+)', $ns . 'AccountController@verifyUser');
        $router->post('/resend-email-verification', $ns . 'AccountController@resendEmaiVerification');

        $router->get('/register', $ns . 'AccountController@showRegisterUser');
        $router->post('/register', $ns . 'AccountController@registerUser');

        $router->before('GET', '/logout', '\App\Middleware\CsrfTokenMiddleware@verifyCsrfToken');
        $router->get('/logout', $ns . 'AccountController@logout');
    }
}
