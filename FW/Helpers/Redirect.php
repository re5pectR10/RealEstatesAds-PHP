<?php

namespace FW\Helpers;


use FW\Input\InputData;
use FW\Route\Route;
use FW\Session\Session;

class Redirect {

    public static function to($uri) {
        header('Location: ' . Common::getBaseURL() . $uri);
        Session::setOldInput(InputData::getInstance()->getPost());
        exit;
    }

    public static function toRoute($name) {
        if (!isset(Route::getRouters()[$name])) {
            throw new \Exception('Not found route with that name', 500);
        }
        $route = Route::getRouters()[$name]['url'];
        self::to($route);
    }

    public static function back() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        Session::setOldInput(InputData::getInstance()->getPost());
        exit;
    }
} 