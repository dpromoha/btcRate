<?php

class Handler {
    public function errorHandle($error) {
        $json = array('message' => $error);
        echo json_encode($json);
    }

    public function messageHandle($key, $value) {
        $json = array($key => $value);
        echo json_encode($json);
    }
}
?>