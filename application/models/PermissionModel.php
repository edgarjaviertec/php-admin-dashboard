<?

class PermissionModel
{
    public function getAll()
    {
        $query = QB::table('permissions')->select('*');
        $row = $query->get();
        return $row;
    }


    public function getById($id)
    {
        $query = QB::table('permissions')->where('id', '=', $id);
        $result = $query->first();
        return $result;
    }


    public function destroy($id)
    {
        QB::table('permissions')->where('id', '=', $id)->delete();
    }


    public function update($id, $perm)
    {





        $data = [
            'name' => $perm['name'],
            'display_name' => $perm['displayName'],
            'description' => $perm['description']
        ];


//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";


        QB::table('permissions')
            ->where('id', $id)
            ->update($data);
    }


    public function store($perm)
    {
        $data = [
            'name' => $perm['name'],
            'display_name' => $perm['displayName'],
            'description' => $perm['description']
        ];
        $insertId = QB::table('permissions')->insert($data);
        return $insertId;
    }


}