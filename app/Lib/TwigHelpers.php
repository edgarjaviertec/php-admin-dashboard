<?php

namespace App\Lib;

use App\Models\RolePermission;
use App\Models\UserRole;

abstract class TwigHelpers
{
    public $view;
    public $emailTemplate;

    public function __construct()
    {
        $app = [
            "session" => $_SESSION,
            "base_url" => strval(BASE_URL)
        ];
        $this->view = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader(config('app_views_folder')), [
            'auto_reload' => true,
            'debug' => true,
            'cache' => false
        ]);
        $this->emailTemplate = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader(config('app_email_templates_folder')), [
            'auto_reload' => true,
            'debug' => true,
            'cache' => false
        ]);
        // Función que comprueba si los permisos del usuario loegueado coinciden con los permisos solicitados
        $ability = new \Twig\TwigFunction('ability', function () {
            // Si hay algún argumento en la función de twig y ademas el usuario esta logueado
            if (count(func_get_args()) > 0 && !empty($_SESSION["logged_in_user"])) {
                $perms = func_get_args();
                // ID del usuario que esta logueado
                $userId = $_SESSION["logged_in_user"]["id"];
                // Arreglo donde se guardaran todos los permisos del usuario logueado
                $allPermissions = [];
                $userRole = new UserRole();
                $RolePermission = new RolePermission();
                // Obtenemos todos los roles que tiene el usuario logueado
                $userRoles = $userRole->getRolesByUser($userId);
                // Obtenemos todos los permisos de todos los roles a los que pertenece el usuario logueado
                foreach ($userRoles as $role) {
                    $rolePermissions = $RolePermission->getPermissionsByRole($role["role_id"]);
                    foreach ($rolePermissions as $permission) {
                        array_push($allPermissions, $permission["permission_name"]);
                    }
                }
                // Si los permisos que solicitan están contenidos dentro de los permisos que tiene el usuario, entonces devolvemos verdadero
                return count(array_intersect($perms, $allPermissions)) == count($perms);
            } else {
                return false;
            }
        });
        $this->view->addFunction($ability);
        $this->view->addGlobal('app', $app);
    }
}