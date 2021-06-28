<?php

define ('DOC_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/');

include_once DOC_ROOT_PATH.'/validation/Handler.php';

class Database extends Handler {

    public $email;
    public $password;
    public $token;

    public function checkToken($token) {
        $dbFile = DOC_ROOT_PATH.'/user/db.xml';
        $isTokenExists = false;

        if (file_exists($dbFile)) {
            $xml = simplexml_load_file($dbFile);
            foreach ($xml as $user) {
                if ($user->token == $token) {
                    $isTokenExists = true;
                    break;
                }
            }
            if ($isTokenExists) {
                $this->getBtcRate();
            } else {
                $this->errorHandle('wrong token');
            }
        } else {
            $this->errorHandle('unauthorized');
        }
    }

    public function getBtcRate() {
        $url = 'https://api.cryptonator.com/api/ticker/btc-uah';

        $options = array(
            'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'GET',
            )
        );
    
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $obj = json_decode($result, true);
        $data = json_decode($obj['ticker']['price'], true);

        if ($result === FALSE) {
            $this->errorHandle('check your internet connection');
        }
        $this->messageHandle('btcRate', $data);
    }
    
    public function loginUser($email, $password) {
        $dbFile = 'db.xml';
        $isUserExists = false;

        if (!file_exists($dbFile)) {
            $this->errorHandle('this email address has not been registered');
            return ;
        } else {
            $xml = simplexml_load_file($dbFile);
            foreach ($xml as $user) {
                if ($user->email == $email) {
                    if ($user->password == $password) {
                        $isUserExists = true;
                        $token = $user->token->__toString();
                        break;
                    } else {
                        $this->errorHandle('incorrect password');
                        return;
                    }
                }
            }
        }

        if ($isUserExists) {
            $this->messageHandle('access token', $token);
        } else {
            $this->errorHandle('this email address has not been registered');
        }
    }

    public function saveUser() {
        $dbFile = 'db.xml';
        $length = 42;
        $token = bin2hex(random_bytes($length));
    
        if (!file_exists($dbFile)) {
            touch($dbFile);
            $xml = new SimpleXMLElement('<users></users>');
        } else {
            $xml = simplexml_load_file($dbFile);
        }
        $user = $xml->addChild('user');
        $user->addChild('email', $this->email);
        $user->addChild('password', $this->password);
        $user->addChild('token', $token);
        
        $xml->asXml($dbFile);
        
        $this->messageHandle('access token', $token);
    }

    public function createUser() {
        if (isset($this->email) && isset($this->password)) {
            $this->saveUser();
        } else {
            $this->errorHandle('can not create user');
        }
    }

    public function isEmailExist($email) {
        $isUserExists = false;
        $dbFile = 'db.xml';
        
        if (file_exists($dbFile)) {
            $xml = simplexml_load_file($dbFile);

            foreach ($xml as $user) {
                if ($user->email == $email) {
                    $isUserExists = true;
                    break;
                }
            }
        }
        return $isUserExists;
    }

    public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public function getAuthorizationHeader(){
        $headers = null;
        
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
           
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
}

?>