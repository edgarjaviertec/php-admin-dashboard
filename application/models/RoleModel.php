<?

class RoleModel
{


    public function getRolesByUser($userId)
    {

        /*
        SELECT
        user_roles.role_id,
        roles.name AS 'role_name'
        FROM roles
        INNER JOIN user_roles ON roles.id = user_roles.role_id
        WHERE user_roles.user_id = 1
        */


        $query = QB::table('roles')
            ->select('user_roles.role_id')
            ->select(QB::raw("roles.name AS 'role_name'"))
            ->join('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('user_roles.user_id', $userId);
        return $query->get();
    }


    public function getAll()
    {
        $query = QB::table('roles')->select(["id", "name"]);
        $result = $query->get();
        return $result;
    }


    public function getById($id)
    {
//        $query = QB::table('permissions')->where('id', '=', $id);
//        $result = $query->first();
//        return $result;
    }


    public function destroy($id)
    {
//        QB::table('permissions')->where('id', '=', $id)->delete();
    }


    public function update($id, $perm)
    {
//        $data = [
//            'name' => $perm['name'],
//            'display_name' => $perm['displayName'],
//            'description' => $perm['description']
//        ];
//        QB::table('permissions')
//            ->where('id', $id)
//            ->update($data);
    }


    public function store($perm)
    {
//        $data = [
//            'name' => $perm['name'],
//            'display_name' => $perm['displayName'],
//            'description' => $perm['description']
//        ];
//        $insertId = QB::table('permissions')->insert($data);
//        return $insertId;
    }


}