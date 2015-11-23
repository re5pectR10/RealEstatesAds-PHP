<?php

namespace FW\View;

use FW\App;

class View {

    private static $viewPath = null;
    private static $viewDir = null;
    private static $data = array();
    private static $extension = '.php';
    private static $layoutParts = array();
    private static $layoutData = array();
    private static $layout = null;
    private static $type = array();
    private  static function initViewPath() {
        
        self::$viewPath = App::getInstance()->getConfig()->app['viewsDirectory'];
        if (self::$viewPath == null) {
            self::$viewPath = realpath('../views/');
        }
    }
    
    public static function setViewDirectory($path) {
        $path = trim($path);
        if ($path) {
            $path = realpath($path) . DIRECTORY_SEPARATOR;
            if (is_dir($path) && is_readable($path)) {
                self::$viewDir = $path;
            } else {
                //todo
                throw new \Exception('view path',500);
            }
        } else {
            //todo
            throw new \Exception('view path',500);
        }
    }

    public static function make($layout, $data = array()) {
        self::$layout = $layout;
        if (is_array($data)) {
            self::$data = array_merge(self::$data, $data);
        }

        return new static;
    }

    public static function getLayoutData($name){
        return self::$layoutData[$name];
    }

    public static function with($key, $data) {
        self::$data[$key] = $data;

        return new static;
    }

    public static function render() {
        self::initViewPath();
        if (!empty(self::$type)) {
            foreach(self::$type as $t) {
                foreach(self::$data as $d) {
                    if (get_class($d) != $t) {
                        throw new \Exception('Wrong object type', 500);
                    }
                }
            }
        }

        if (count(self::$layoutParts) > 0) {
            foreach (self::$layoutParts as $k => $v) {
                $r = self::_includeFile($v);
                if ($r) {
                    self::$layoutData[$k] = $r;
                }
            }
        }
        if (self::$layout !== null) {
            echo self::_includeFile(self::$layout);
        } else {
            throw new \Exception('The layout is missing', 500);
        }
    }

    public static function useType(array $class = array()) {
        foreach($class as $c) {
            if(!class_exists($c)) {
                throw new \Exception('The class' . $class . 'is not defined', 500);
            }

            self::$type[] = $c;
        }
        return new static;
    }

    public static function removeType() {
        self::$type = null;
        return new static;
    }

    public static function appendTemplateToLayout($key, $template) {
        if ($key && $template) {
            self::$layoutParts[$key] = $template;
        } else {
            throw new \Exception('Layout required valid key and template', 500);
        }

        return new static;
    }

    private static function _includeFile($___file) {
        if (self::$viewDir == null) {
            self::setViewDirectory(self::$viewPath);
        }       
        $___fl = self::$viewDir . str_replace('.', DIRECTORY_SEPARATOR, $___file) . self::$extension;
        if (file_exists($___fl) && is_readable($___fl)) {
            ob_start();
            extract(self::$data);
            include $___fl;            
            return ob_get_clean();
        } else {
            throw new \Exception('View ' . $___file . ' cannot be included', 500);
        }        
    }
}


