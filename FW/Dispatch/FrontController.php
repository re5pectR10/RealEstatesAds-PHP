<?php

namespace FW\Dispatch;

use FW\App;
use FW\Helpers\Common;
use FW\Helpers\DependencyProvider;
use FW\Input\InputData;
use FW\Route\IRequestProvider;
use FW\Route\RequestProvider;
use FW\Route\Route;
use FW\Security\Auth;
use FW\Security\CSRF;
use FW\Session\Session;

class FrontController implements IDispatcher {

    private static $instance = null;
    /**
     * @var \FW\Route\IRequestProvider
     */
    private $request = null;

    public function __construct(IRequestProvider $request = null) {
        if ($request !== null) {
            $this->request = $request;
        } else {
            $this->request = new RequestProvider();
        }
    }

    public function setRequestProvider(IRequestProvider $request) {
        $this->request = $request;
    }

    public function dispatch(){
        $uri = $this->request->getURI();
        $uriParams = array_filter(explode('/', $uri), 'strlen');
        $controllerName = '';
        $controllerMethod = '';
        $paramsFromGET = array();
        foreach(Route::getRouters() as $route){
            $paramsFromGET = array();
            if($route['method'] != $_SERVER['REQUEST_METHOD'] ){
                continue;
            }

            if (in_array('auth', explode('|', $route['details']['before']))) {
                if (!Auth::isAuth()) {
                    continue;
                }
            }

            if (!Auth::isUserInRole(array_filter(explode('|', $route['details']['roles']), 'strlen'))) {
                continue;
            }

            $routeParams = array_filter(explode('/', $route['url']), 'strlen');
            $nonRequiredFieldsForRoute = $this->getNonRequiredFieldsCount($routeParams);
            if(count($uriParams) < count($routeParams) - $nonRequiredFieldsForRoute || count($uriParams) > count($routeParams)) {
                continue;
            }
            for($i = 0; $i < count($uriParams); $i++) {
                if(!Common::startsWith($routeParams[$i], '{') && !Common::endsWith($routeParams[$i], '}')) {
                    if($uriParams[$i]!=$routeParams[$i]){
                        continue 2;
                    }
                } else {
                    if(!$this->isParameterValid($uriParams[$i], $routeParams[$i])) {
                        continue 2;
                    }

                    $paramName = $this->getParameterName($routeParams[$i]);
                    $paramsFromGET[$paramName] = $uriParams[$i];
                }

                if(count($uriParams)-1 == $i) {
                    $controllerData = explode('@', $route['details']['use']);
                    $controllerName = App::getInstance()->getConfig()->app['controllers_namespace']. '\\'.$controllerData[0];
                    $controllerMethod = $controllerData[1];
                    break 2;
                }
            }

            $paramsFromGET = array();
            if (in_array('csrf', explode('|', $route['details']['before']))) {
                if (!CSRF::validateToken()) {
                    continue;
                }
            }
        }
        if($controllerMethod === '') {
            if(App::getInstance()->getConfig()->app['enable_default_routing']) {
                $controllerName = App::getInstance()->getConfig()->app['controllers_namespace']. '\\'.$uriParams[0].'Controller';
                $controllerMethod = $uriParams[1];
                $r = new \ReflectionMethod($controllerName, $controllerMethod);
                $params = $r->getParameters();
                $index = 2;
                foreach ($params as $param) {
                    $paramsFromGET[$param->name] = $uriParams[$index];
                    $index++;
                }
                for($i = $index; $i < count($uriParams); $i++) {
                    $paramsFromGET[$i] = $uriParams[$i];
                }
            } else {
                $controllerName = App::getInstance()->getConfig()->app['controllers_namespace']. '\\'.App::getInstance()->getConfig()->app['default_controller'];
                $controllerMethod = App::getInstance()->getConfig()->app['default_method'];
            }
        }

        $requestInput = $this->bindDataToControllerMethod($paramsFromGET, $controllerName, $controllerMethod);
        $controller = new $controllerName();
        $controller = DependencyProvider::injectDependenciesToController($controller);
        call_user_func_array(array($controller, $controllerMethod), $requestInput);
        Session::setOldInput(InputData::getInstance()->getPost());
    }

    public function bindDataToControllerMethod($paramsFromGET, $controllerName, $controllerMethod) {
        $input = InputData::getInstance();
        $input->setGet($paramsFromGET);
        $input->setPost($this->request->getPOST());
        $class = new \ReflectionClass($controllerName);
        $method=$class->getMethod($controllerMethod);
        $methodRequiredParams = $method->getNumberOfRequiredParameters();
        $methodParams = $method->getParameters();
        $requestInput = array();
        foreach($methodParams as $par) {
            if ($par->getClass()->name !== null) {
                $paramClass = new \ReflectionClass($par->getClass()->name);
                $paramClassProperties = $paramClass->getProperties();
                $paramClassInstance = new $paramClass->name();
                foreach($paramClassProperties as $property) {
                    foreach($input->getPost() as $key => $value) {
                        $propertyName = $property->name;
                        if ($property->name == $key) {
                            $paramClassInstance->$propertyName = $value;
                        }
                    }
                }

                $requestInput[$par->name] = $paramClassInstance;
            }

            if(isset($input->getGet()[$par->name])) {
                $requestInput[$par->name] = $input->getGet()[$par->name];
            } else if(isset($input->getPost()[$par->name])) {
                $requestInput[$par->name] = $input->getPost()[$par->name];
            }
        }
        if($methodRequiredParams > count($requestInput)) {
            throw new \Exception('parameters in the request not equal to parameters declared in method', 500);
        }

        return $requestInput;
    }

    public function getParameterName($param) {
        $paramName = explode(':', $param)[0];
        $paramName = substr($paramName, 1);
        if(Common::endsWith($paramName, '?}')) {
            $paramName = substr($paramName, 0, strlen($paramName) - 2);
        } else if(Common::endsWith($paramName, '}')){
            $paramName = substr($paramName, 0, strlen($paramName) - 1);
        }

        return $paramName;
    }

    private function isParameterValid($paramFromUrl, $paramFromRoute) {
        $split = explode(':', $paramFromRoute);
        if(!isset($split[1])) {
            return true;
        }
        if(Common::startsWith($split[1], 'int')) {
            return is_numeric($paramFromUrl) && $paramFromUrl == ceil($paramFromUrl);
        }
        if(Common::startsWith($split[1], 'bool')) {
            return is_bool($paramFromUrl);
        }
        if(Common::startsWith($split[1], 'float')) {
            return is_numeric($paramFromUrl);
        }
        if(Common::startsWith($split[1], 'double')) {
            return is_numeric($paramFromUrl);
        }
        if(Common::startsWith($split[1], 'long')) {
            return is_long($paramFromUrl);
        }
        return false;
    }

    private function getNonRequiredFieldsCount($routeParams) {
        $paramsCount = 0;
        for($i = count($routeParams) - 1; $i >= 0; $i--) {
            if(Common::endsWith($routeParams[$i], '?}')) {
                $paramsCount++;
            } else {
                return $paramsCount;
            }
        }

        return $paramsCount;
    }

    public function getDefaultController() {
        $controller = App::getInstance()->getConfig()->app['default_controller'];
        if ($controller) {
            return strtolower($controller);
        }
        return 'index';
    }

    public function getDefaultMethod() {
        $method = App::getInstance()->getConfig()->app['default_method'];
        if ($method) {
            return strtolower($method);
        }
        return 'index';
    }

    /**
     * 
     * @return \FW\Dispatch\FrontController
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new FrontController();
        }
        return self::$instance;
    }

}

