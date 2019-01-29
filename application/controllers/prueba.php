<?php
class Prueba
{
    public  function hola($parametro) {
        if($parametro!=="bueno"){
            header("HTTP/1.1 401 Unauthorized");
            $data = array(
                "statusCode" => "401",
                "message" => "Unauthorized",
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }else{
            header("HTTP/1.1 200 OK");
        }
    }
}
?>