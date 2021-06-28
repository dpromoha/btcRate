<?php

class UserValidation {

    public function isValidEmail($email) { 
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function isValidPassword($password) {
        return strlen($password) >= 6 && strlen($password) <= 42;
    }
}
?>