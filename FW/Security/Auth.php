<?php

namespace FW\Security;

use FW\App;
use FW\Database\DB;

class Auth {

    public static function isAuth() {
        return isset($_SESSION['id']);
    }

    public static function getUserId() {
        if (self::isAuth()) {
            return $_SESSION['id'];
        }

        return null;
    }

    public static function setAuth($id) {
        $_SESSION['id'] = $id;
    }

    public static function removeAuth() {
        if (isset($_SESSION['id'])) {
            unset($_SESSION['id']);
        }
    }

    public static function isUserInRole(array $roles = array()) {
        if (empty($roles)) {
            return true;
        }

        if (!self::isAuth()) {
            return false;
        }

        $appInstance = App::getInstance();
        $userRole = new DB();
        $userRole = $userRole
            ->prepare('Select r.' .
                $appInstance->getConfig()->app['role_table']['role_name_column'] .
                ' From ' .
                $appInstance->getConfig()->app['role_table']['name'] .
                ' as r join ' .  $appInstance->getConfig()->app['user_role_table']['name'] .
                ' as ur on ur.' .
                $appInstance->getConfig()->app['user_role_table']['role_id_column'] .
                '=r.' . $appInstance->getConfig()->app['role_table']['id_column'] .
                ' where ur.' .
                $appInstance->getConfig()->app['user_role_table']['user_id_column'] .
                '=?');
        $userRole->execute(array(self::getUserId()));

        foreach($userRole->fetchAllAssoc() as $role) {
            if (in_array($role[$appInstance->getConfig()->app['role_table']['role_name_column']], $roles)) {
                return true;
            }
        }

        return false;
    }

    public static function validateUser($username, $password) {
        $appInstance = App::getInstance();
        $user = new DB();
        $user = $user
            ->prepare('Select ' .
                $appInstance->getConfig()->app['user_table']['id'] .
                ', '.
                $appInstance->getConfig()->app['user_table']['password'].
                ' From ' .
                $appInstance->getConfig()->app['user_table']['name'] .
                ' where ' .
                $appInstance->getConfig()->app['user_table']['username'] .
                '=?');
        $user->execute(array($username));
        $result = $user->fetchAllAssoc();
        if (count($result) > 1) {
            throw new \Exception('there are more than 1 user with this credentials', 500);
        }
        if (count($result) < 1) {
            return false;
        }
        if (!password_verify($password, $result[0][$appInstance->getConfig()->app['user_table']['password']])) {
            return false;
        }

        $_SESSION['id'] = $result[0][$appInstance->getConfig()->app['user_table']['id']];
        return true;
    }
} 