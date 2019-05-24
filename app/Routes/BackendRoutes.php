<?php

namespace App\Routes;

class BackendRoutes
{
    public function loadRoutes($router)
    {
        // Rutas para el panel de administración
        $router->mount('/backend', function () use ($router) {
            $ns = "\App\Controllers\Backend\\";
            $router->get('/', $ns . 'HomeController@index');
            // Rutas para la sección "Permisos"
            $router->post('/permissions/destroy', $ns . 'PermissionController@deletePermission');
            $router->post('/permissions/update', $ns . 'PermissionController@updatePermission');
            $router->post('/permissions/store', $ns . 'PermissionController@createPermission');
            $router->get('/permissions', $ns . 'PermissionController@index');
            $router->get('/permissions/(\d+)/edit', $ns . 'PermissionController@displayEditView');
            $router->get('/permissions/create', $ns . 'PermissionController@displayNewView');

            // Rutas para la sección "Usuarios"
            $router->get('/users', $ns . 'UserController@index');
            $router->get('/users/new', $ns . 'UserController@displayNewView');
            $router->post('/users/create', $ns . 'UserController@createUser');
            $router->get('/users/(\d+)/edit', $ns . 'UserController@displayEditView');
            $router->post('/users/update', $ns . 'UserController@updateUser');
            $router->post('/users/delete', $ns . 'UserController@deleteUser');

            // Rutas para la sección "Usuarios"
            $router->get('/roles', $ns . 'RoleController@index');

            $router->get('/roles/new', $ns . 'RoleController@displayNewView');
            $router->post('/roles/create', $ns . 'RoleController@createRole');

            $router->get('/roles/(\d+)/edit', $ns . 'RoleController@displayEditView');
            $router->post('/roles/update', $ns . 'RoleController@updateRole');
            $router->post('/roles/delete', $ns . 'RoleController@deleteRole');

            $router->get('/users/(\d+)/change-password', $ns . 'UserController@displayChangePasswordView');
            $router->post('/users/change-password', $ns . 'UserController@changeUserPassword');
            $router->get('/assign-permissions', $ns . 'RolePermissionController@index');
            $router->post('/assign-permissions', $ns . 'RolePermissionController@assignPermissions');
        });
    }
}
