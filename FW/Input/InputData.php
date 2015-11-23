<?php

namespace FW\Input;

use FW\App;
use FW\Helpers\Common;

class InputData {

    private static $_instance = null;
    private $_get = array();
    private $_post = array();
    private $_cookies = array();
    private $isGlobalEscapingEnable = true;
    private $except = array();

    private function __construct() {
        $this->_cookies = $_COOKIE;
        if(isset(App::getInstance()->getConfig()->app['global_input_escaping'])) {
            $this->isGlobalEscapingEnable = App::getInstance()->getConfig()->app['global_input_escaping'];
        }

        if (isset(App::getInstance()->getConfig()->app['escape_input_without']) && is_array(App::getInstance()->getConfig()->app['escape_input_without'])) {
            $this->except = App::getInstance()->getConfig()->app['escape_input_without'];
        }
    }

    public function setPost($ar) {
        if (is_array($ar)) {
            if ($this->isGlobalEscapingEnable) {
                foreach($ar as $key=>$value) {
                    if (in_array($key, $this->except)) {
                        $this->_post[$key] = $value;
                    } else {
                        $this->_post[$key] = Common::xss_clean($value);
                    }
                }
            } else {
                $this->_post = $ar;
            }
        }
    }

    public function setGet($ar) {
        if (is_array($ar)) {
            if ($this->isGlobalEscapingEnable) {
                foreach($ar as $key=>$value) {
                    $this->_get[$key] = Common::xss_clean($value);
                }
            } else {
                $this->_get = $ar;
            }
        }
    }

    public function getGet() {
        return $this->_get;
    }

    public function getPost() {
        return $this->_post;
    }

    public function hasGet($id) {
        return array_key_exists($id, $this->_get);
    }

    public function hasPost($name) {
        return array_key_exists($name, $this->_post);
    }
    
    public function hasCookies($name) {
        return array_key_exists($name, $this->_cookies);
    }

    public function get($id, $normalize = null, $default = null) {
        if ($this->hasGet($id)) {
            if ($normalize != null) {
                return Common::normalize($this->_get[$id], $normalize);
            }
            return $this->_get[$id];
        }
        return $default;        
    }
    
    public function post($name, $normalize = null, $default = null) {
        if ($this->hasPost($name)) {
            if ($normalize != null) {
                return Common::normalize($this->_post[$name], $normalize);
            }
            return $this->_post[$name];
        }
        return $default;        
    }
    
    public function cookies($name, $normalize = null, $default = null) {
        if ($this->hasCookies($name)) {
            if ($normalize != null) {
                return Common::normalize($this->_cookies[$name], $normalize);
            }
            return $this->_cookies[$name];
        }
        return $default;        
    }

    /**
     * 
     * @return \FW\Input\InputData
     */
    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new InputData();
        }
        return self::$_instance;
    }

}

