<?php
namespace FW;

use FW\Dispatch\FrontController;
use FW\Helpers\Common;
use FW\Helpers\Config;
use FW\Session\Session;
use FW\View\View;

include_once 'Loader.php';


class App {

    private static $instance = null;
    private $config = null;
    private $router = null;

    /**
     *
     * @var \FW\Dispatch\FrontController
     */
    private $frontController = null;

    private function __construct() {
        set_exception_handler(array($this, '_exceptionHandler'));
        Loader::registerNamespace('FW', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        Loader::registerAutoLoad();
        $this->config = Config::getInstance();
        if ($this->config->getConfigFolder() == null) {
            $this->setConfigFolder('../config');
        }
    }

    public function setConfigFolder($path) {
        $this->config->setConfigFolder($path);
    }

    private function setRoutes() {
        include_once '../routes.php';
    }

    private function setDependancies() {
        include_once '../dependencies.php';
    }

    public function getConfigFolder() {
        return $this->config->getConfigFolder();
    }

    public function getRouter() {
        return $this->router;
    }

    public function setRouter($router) {
        $this->router = $router;
    }

    /**
     * 
     * @return \FW\Helpers\Config
     */
    public function getConfig() {
        return $this->config;
    }

    public function run() {
        if ($this->config->getConfigFolder() == null) {
            $this->setConfigFolder('../app/config');
        }
        $this->setRoutes();
        $this->setDependancies();
        $this->frontController = FrontController::getInstance();
        //$this->frontController->setURI(substr($_SERVER["PHP_SELF"], strlen($_SERVER['SCRIPT_NAME']) + 1));

        $_sess = $this->config->app['session'];
        if ($_sess['autostart']) {
            Session::setSession($_sess['name'], $_sess['lifetime'], $_sess['path'], $_sess['domain'], $_sess['secure']);
        }

        $this->frontController->dispatch();
    }

    /**
     * 
     * @return \FW\App
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new App();
        }
        return self::$instance;
    }
    
    public function _exceptionHandler(\Exception $ex) {        
        if ($this->config && $this->config->app['debug'] == true) {
            echo '<pre>' . print_r($ex, true) . '</pre>';
        } else {
            $this->displayError($ex->getCode());
        }
    }

    public function displayError($error) {
        try {
            View::make('errors.' . $error)->render();
        } catch (\Exception $exc) {
            Common::headerStatus($error);
            echo '<h1>' . $error . '</h1>';
            exit;
        }
    }
    
    public function __destruct() {
        Session::saveSession();
    }
}