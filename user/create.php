<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../database/Database.php';
    include_once '../validation/userValidation.php';
    include_once '../validation/Handler.php';

    $db = new Database();
    $valid = new UserValidation();
    $eh = new Handler();

    $index = 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"));
       
        foreach ($data as $x) {
            $index+=1;
        }

        if ($index != 2) {
            $eh->errorHandle('incorrect body');
        } else {
            if (isset($data->email) && isset($data->password)) {
                if ($valid->isValidEmail($data->email)) {
                    if ($db->isEmailExist($data->email)) {
                        $eh->errorHandle('this email address is already in use');
                    } else {
                        if ($valid->isValidPassword($data->password)) {
                            $db->email = $data->email;
                            $db->password = $data->password;
                            $db->createUser();
                        } else {
                            $eh->errorHandle('your password must be between 6 and 42 characters');
                        }
                    }
                } else {
                    $eh->errorHandle('please, choose valid email address');
                }
            } else {
                $eh->errorHandle('incorrect body');
            }
        }
    } else {
        $eh->errorHandle('incorrect type of request method, please, use POST');
    }
?>