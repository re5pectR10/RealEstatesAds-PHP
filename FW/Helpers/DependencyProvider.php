<?php

namespace FW\Helpers;


class DependencyProvider {

    private static $dependencies = array();

    public static function inject($controllerName, $propertyName, $type, $propertyClass, $dependency = null) {
        self::$dependencies[$controllerName][] = array(
            'name' => $propertyName,
            'type' => $type,
            'class' => $propertyClass,
            'dependencies' => $dependency
        );
    }

    public static function injectDependenciesToController($controller) {
        if ($controller == null) {
            return null;
        }

        $dependenciesData = array();
        $reflection = new \ReflectionClass($controller);
        if (isset(self::$dependencies[$reflection->name])) {
            $dependenciesData = self::$dependencies[$reflection->name];
        }

        foreach($dependenciesData as $item) {
            $reflectionProperty = $reflection->getProperty($item['name']);
            $reflectionProperty->setAccessible(true);
            if (isset($item['type'])) {
                include_once dirname(__DIR__) . '\app\\'.$item['type'] .'.php';
            }
            $newClassInstance = new $item['class']();
            if ($item['dependencies'] instanceof \Closure) {
                call_user_func($item['dependencies']);
            }

            $newClassInstance = self::injectDependenciesToController($newClassInstance);
            $reflectionProperty->setValue($controller,$newClassInstance);
        }

        return $controller;
    }
} 