<?php

namespace FW\Helpers;
use Controllers;
use FW\App;

class HelpPage {

    private $routes = array();
    private $output = array();

    public function __construct(array $routes = array()) {
        $this->routes = $routes;
        $this->init();
    }

    private function init() {
        $index = 0;
        foreach($this->routes as $r) {
            $this->output[$index]['url'] = $r['url'];
            $this->output[$index]['method'] = $r['method'];
            $controllerData = explode('@', $r['details']['use']);
            $paramsToSerialize = array();

            $class = new \ReflectionClass(App::getInstance()->getConfig()->app['controllers_namespace']. '\\'.$controllerData[0]);
            $method=$class->getMethod($controllerData[1]);
            $methodParams = $method->getParameters();
            foreach($methodParams as $par) {
                if ($par->getClass()->name !== null) {
                    $paramClass = new \ReflectionClass($par->getClass()->name);
                    //$this->output[$index]['params'][$par->getClass()->name] = array();
                    $classInstance = new $paramClass->name();
                    foreach($classInstance as $q=>$e) {
                        //$paramsToSerialize['params'][$par->getClass()->name][$q] = $q;
                        $paramsToSerialize['params'][$q] = $q;
                    }
                } else {
                    $paramsToSerialize['params'][$par->name] = $par->name;
                }
            }

            $this->output[$index]['params'] = json_encode($paramsToSerialize['params'],  JSON_PRETTY_PRINT);
            $index++;
        }
    }

    public function getData() {
        return $this->output;
    }

    public function getByIndex($index) {
        return $this->output[$index];
    }
} 