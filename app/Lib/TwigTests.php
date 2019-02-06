<?php

namespace App\Lib;

use App\Models\RolePermission;

class TwigTests
{
    public function userPermissionsTest()
    {

        $test = new \Twig_SimpleTest('inUserPermissions', function ($value) {
            $all = ["permiso1", "permiso2", "permiso5"];

            $rolePermissionModel = new RolePermission();
            $allPermissionsByRole = $rolePermissionModel->getPermissionsByRole(1);
            $permissionsName = [];

            foreach ($allPermissionsByRole as $permission) {
                array_push($permissionsName, $permission["permission_name"]);
            }

            return count(array_intersect($value, $all)) == count($value);
        });
        return $test;
    }
}