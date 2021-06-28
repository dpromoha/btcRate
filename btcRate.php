<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once 'database/Database.php';

    $db = new Database();

    $accessToken = $db->getBearerToken();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($accessToken)) {
            $db->checkToken($accessToken);
        } else {
            $db->errorHandle('please, set an access (bearer) token');
        }
    } else {
        $db->errorHandle('incorrect type of request method, please, use GET');
    }
?>