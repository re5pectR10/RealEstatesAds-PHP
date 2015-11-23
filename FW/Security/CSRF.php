<?php

namespace FW\Security;


class CSRF {

    public static function generateToken() {
        $token = md5(uniqid(rand(), true));
        //Session::set('_token', $token);
        $_SESSION['_token'] = $token;
        return $token;
    }

    public static function validateToken() {
        if (isset($_POST['_token'])) {
            if ($_POST['_token'] == $_SESSION['_token']) {
                unset($_SESSION['_token']);
                return true;
            }
        }

        return false;
    }
} 