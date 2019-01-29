<?
class LoginModel{
    public function getUser($email){
        $query = QB::table('users')->where('email', '=', $email);
        $row = $query->first();
        return $row;
    }
}