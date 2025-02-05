<?php

namespace Framework\Routing;

use Closure;
use Exception;
use Framework\DependencyInjection\DependencyManagerInterface;
use Framework\Handling\MiddlewareInterface;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Routing\Attributes\Route as RouteAttribute;
use Framework\Routing\Controllers\BaseController;
use Framework\Routing\Controllers\NotFoundController;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Router implements MiddlewareInterface
{

    /**
     * @param DependencyManagerInterface $dm
     * @return void
     * @throws ReflectionException
     */
    public static function execute(DependencyManagerInterface $dm): void
    {
        $routes = self::buildRoutesList();
        self::validateRoutesList($routes);

        $matchedRoute = new Route(
            name: "DEFAULT_NOTFOUND",
            path: "*",
            methods: Request::METHODS,
            controller: NotFoundController::class,
            method: "index"
        );


        $request = $dm->getDependency(Request::class);
        foreach ($routes as $route) {
            if ($route->matches($request)) {
                $matchedRoute = $route;
                break;
            }
        }

        $dm->setDependency(Response::class, $matchedRoute->execute($dm));
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function buildRoutesList(): array
    {
        $directory = dirname($_SERVER["DOCUMENT_ROOT"]) . "/src/Controller/";
        $namespace = "App\\Controller";

        $files = scandir($directory);
        $classes = [];

        foreach ($files as $file) {
            if (in_array($file, [
                ".",
                ".."
            ])) {
                continue;
            }

            if (!is_file($directory . $file) || pathinfo($file, PATHINFO_EXTENSION) !== "php") {
                continue;
            }

            $class = $namespace . "\\" . pathinfo($file, PATHINFO_FILENAME);
            if (!class_exists($class) || !is_subclass_of($class, BaseController::class)) {
                continue;
            }

            $classes[] = $class;
        }

        $routes = [];

        foreach ($classes as $class) {
            $routes = array_merge($routes, self::getRoutesForClass(new ReflectionClass($class)));
        }

        return $routes;
    }

    public static function validateRoutesList(array $routes): void
    {
        $names = [];
        $paths = [];

        /** @var Route $route */
        foreach ($routes as $route) {
            $handlerID = $route->getController() . "->" . $route->getMethod();

            if (isset($names[$route->getName()])) {
                throw new Exception("Multiple definitions for route with name \"" . $route->getName() . "\"");
            }

            foreach ($route->getMethods() as $httpMethod) {
                $pathSignature = $httpMethod . "->" . $route->getPathRegex();

                if (isset($paths[$pathSignature]) && $paths[$pathSignature] !== $handlerID) {
                    throw new Exception("Multiple definitions for route with path " . $httpMethod .  " \"" . $route->getPath() . "\"");
                }

                $paths[$pathSignature] = $handlerID;
            }

            $names[$route->getName()] = $handlerID;
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    public static function getRoutesForClass(ReflectionClass $reflectionClass): array
    {
        $routes = [];

        $reflectionMethods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($reflectionMethods as $reflectionMethod) {
            $routes = array_merge($routes, self::getRoutesForMethod($reflectionMethod));
        }

        return $routes;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return array
     */
    public static function getRoutesForMethod(ReflectionMethod $reflectionMethod): array
    {
        $routes = [];
        $reflectionAttributes = $reflectionMethod->getAttributes(RouteAttribute::class);

        foreach ($reflectionAttributes as $reflectionAttribute) {
            /** @var RouteAttribute $routeAttribute */
            $routeAttribute = $reflectionAttribute->newInstance();

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

    public static function middleware(DependencyManagerInterface $dm, Closure $next): void
    {
        self::execute($dm);
        $next();
    }

}