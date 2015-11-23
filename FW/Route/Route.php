<?php

namespace FW\Route;

class Route {

    private static $routes = array();
    private static $prefix = '';
    private static $index = 0;
    private static $details = array();

    public static function GET($url, $details = array()){
        self::addRoute('GET', $url, $details);
    }

    public static function POST($url, $details = array()){
        self::addRoute('POST', $url, $details);
    }

    public static function PUT($url, $details = array()){
        self::addRoute('PUT', $url, $details);
    }

    public static function DELETE($url, $details = array()){
        self::addRoute('DELETE', $url, $details);
    }

    public static function Group($prefix, $details = array(), $func){
        if ($func instanceof \Closure) {
            self::$prefix .= $prefix;
            if (!empty(self::$details)) {
                $previousDetails = self::$details[count(self::$details) - 1];
                foreach($details as $key => $value) {
                    if ($key == 'roles' || $key == 'before') {
                        $previousDetails[$key] .= '|' . $value;
                    }
                }
                self::$details[] = $previousDetails;
            } else {
                self::$details[] = $details;
            }
            call_user_func($func);
            array_pop(self::$details);
            self::$prefix = substr(self::$prefix, 0, strlen(self::$prefix) - strlen($prefix));
        } else {
            throw new \Exception('Invalid routes function', 500);
        }
    }

    public static function getRouters(){
        return self::$routes;
    }

    private static function addRoute($type, $url, $details = array()) {
        if (isset($details['name'])) {
            if ($details['name'] == '') {
                throw new \Exception('The route name can not be empty string', 500);
            }
            if (array_key_exists($details['name'], self::$routes)) {
                throw new \Exception('There are duplicate route names', 500);
            }

            $key = $details['name'];
        } else {
            $key = self::$index;
            self::$index++;
        }
        if (!empty(self::$details)) {
            foreach(self::$details[count(self::$details) - 1] as $k => $value) {
                $details[$k] .= '|' . $value;
            }
        }

        self::$routes[$key] = array('url' => self::$prefix . $url, 'details' => $details, 'method' => $type);
    }
}