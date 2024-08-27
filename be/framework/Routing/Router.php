<?php

namespace Framework\Routing;

use Exception;
use Framework\Context;
use Framework\HTTP\Request;
use Framework\Routing\Attributes\Route as RouteAttribute;
use Framework\Routing\Controllers\BaseController;
use Framework\Routing\Controllers\NotFoundController;
use ReflectionClass;
use ReflectionMethod;

class Router
{

    /**
     * @return void
     */
    public static function execute(): void
    {

        $routes = self::buildRotesList();
        self::validateRoutesList($routes);

        $matchedRoute = new Route(
            name: "DEFAULT_NOTFOUND",
            path: "*",
            methods: Request::METHODS,
            controller: NotFoundController::class,
            method: "index"
        );

        foreach ($routes as $route){
            if($route->matches(Context::$request)){
                $matchedRoute = $route;
                break;
            }
        }

        Context::$response =  $matchedRoute->execute(Context::$request);
        return;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function buildRotesList() : array
    {
        $namespace = "App\\Controller";
        $directory = dirname($_SERVER["DOCUMENT_ROOT"]) . "/src/Controller/";

        $files = scandir($directory);
        $classes = [];

        foreach ($files as $file) {
            if(in_array($file, [".", ".."])){
                continue;
            }
            if (!is_file($directory . $file) || pathinfo($file, PATHINFO_EXTENSION) !== "php") {
                continue;
            }
            $class = $namespace . "\\" . pathinfo($file, PATHINFO_FILENAME);

            if (!class_exists($class) || !is_subclass_of($class, BaseController::class)){
                continue;
            }

            $classes[] = $class;
        }
        $routes = [];

        foreach ($classes as $class){
            $routes = array_merge($routes, self::getRoutesForClass(new ReflectionClass($class)));
        }
        return $routes;
    }


    public static function validateRoutesList(array $routes): void
    {
        $names = [];
        $regexes = [];

        /** @var Route $route */
        foreach ($routes as $route){
            $methodID = $route->getController() . "->" . $route->getMethod();


            if (isset($names[$route->getName()])) {
                throw new Exception("Multiple definitions for route with name \" {$route->getName()} \" ");
            }

            if (isset($regexes[$route->getPathRegex()]) && $regexes[$route->getPathRegex()] !== $methodID ) {
                throw new Exception("Multiple definitions for route with path \" {$route->getPath()} \" ");
            }

            $names[$route->getName()] = $methodID;
            $regexes[$route->getPathRegex()] = $methodID;

        }
    }


    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    public static function getRoutesForClass(ReflectionClass $reflectionClass) : array
    {
        $routes = [];
        $reflectionMethods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($reflectionMethods as $reflectionMethod){
             $routes = array_merge($routes, self::getRoutesForMethod($reflectionMethod));
        }
        return $routes;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public static function getRoutesForMethod(ReflectionMethod $reflectionMethod) : array
    {
        $routes = [];
        $reflectionsAttributes = $reflectionMethod->getAttributes(RouteAttribute::class);

        foreach ($reflectionsAttributes as $reflectionsAttribute) {
            /** @var RouteAttribute $routeAttribute */
            $routeAttribute = $reflectionsAttribute->newInstance();

            $route = new Route(
                name: $routeAttribute->getName(),
                path: $routeAttribute->getPath(),
                methods: $routeAttribute->getMethods(),
                controller: $reflectionMethod->class,
                method: $reflectionMethod->name
            );

            $routes[] = $route;
        }
        return $routes;
    }

}