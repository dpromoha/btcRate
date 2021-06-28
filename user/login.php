<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../database/Database.php';
    include_once '../validation/Handler.php';

    $db = new Database();
    $eh = new Handler();

    $index = 0;

    $data = json_decode(file_get_contents("php://input"));

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($data as $x) {
            $index+=1;
        }
    
        if ($index != 2) {
            $eh->errorHandle('incorrect body');
        } else {
            if (isset($data->email) && isset($data->password)) {
                $db->email = $data->email;
                $db->password = $data->password;
                $db->loginUser($data->email, $data->password);
            } else {
                $eh->errorHandle('incorrect body');
            }
        }
    } else {
        $eh->errorHandle('incorrect type of request method, please, use POST');
    }
?>