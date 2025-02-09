<?php

namespace Framework\HTTP;

use Closure;
use Framework\Config\Config;
use Framework\DependencyInjection\DependencyManagerInterface;
use Framework\Handling\MiddlewareInterface;

class Cors implements MiddlewareInterface
{

    public static function middleware(DependencyManagerInterface $dependencyManager, Closure $next): void
    {

        /** @var Request $req */
        $req = $dependencyManager->getDependency(Request::class);

        header("Access-Control-Allow-Origin: " . (@Config::$config["routing"]["cors"]["allow_origin"] ?? ""));

        if ($req->getMethod() !== Request::METHOD_OPTION){
            $next();
            return;
        }

        header("Access-Control-Allow-Methods: " . (@Config::$config["routing"]["cors"]["allow_methods"] ?? ""));
        header("Access-Control-Allow-Headers: " . (@Config::$config["routing"]["cors"]["allow_headers"] ?? ""));
        $dependencyManager->setDependency(Response::class, new Response(null, 200));
    }
}