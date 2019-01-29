<?php


require_once  __DIR__ . '/../models/RoleModel.php';
require_once  __DIR__ . '/../models/RolePermissionModel.php';

class TwigTests
{
    public function userPermissionsTest()
    {

        $roleModel = new RoleModel();
        $rolePermissionModel = new RolePermissionModel();


        $rolesByUser =  $roleModel->getRolesByUser(1);
        $allPermissionsByRole =  $rolePermissionModel->getPermissionsArrayByRole(1);

        echo "<pre>";
        print_r($allPermissionsByRole);
        echo "</pre>";






        $test = new Twig_SimpleTest('inUserPermissions', function ($value) {
            $all = ["permiso1","permiso2","permiso5"];
            return count(array_intersect($value, $all)) == count($value);
        });
        return $test;
    }
}