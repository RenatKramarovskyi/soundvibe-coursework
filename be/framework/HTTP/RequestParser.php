<?php

namespace Framework\HTTP;

use Closure;
use Exception;
use Framework\DependencyInjection\DependencyManagerInterface;
use Framework\Handling\MiddlewareInterface;

class RequestParser implements MiddlewareInterface
{
    /**
     * @param DependencyManagerInterface $dm
     * @return void
     * @throws Exception
     */
    public static function parseRequest(DependencyManagerInterface $dm) : void
    {

        $request = $dm->getDependency(Request::class);
        $headers = getallheaders();
        $content = null;


        if ($headers["Content-Type"] == "application/json") {
            $content = json_decode(file_get_contents("php://input"), true);
        }

        $globalPrefix = "/api";


        $path = strstr($_SERVER["REQUEST_URI"] . "?", "?", true);
        if(!str_starts_with($path, $globalPrefix)){
            throw new \Exception("Path \"" . $path ."\" does not starts with global prefix" . $globalPrefix);
        }
        $path = rtrim(substr($path, strlen($globalPrefix)), "/");

        $protocol = empty($_SERVER["HTTPS"]) ? "http" : "https" ;


        $request->setMethod($_SERVER["REQUEST_METHOD"])
            ->setHeaders($headers)
            ->setQuery($_GET)
            ->setContent($content)
            ->setPath($path)
            ->setUrl("$protocol://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    }

    public static function middleware(DependencyManagerInterface $dependencyManager, Closure $next): void
    {
        self::parseRequest($dependencyManager);
        $next();
    }
}