<?php

namespace Framework\HTTP;

use Exception;
class RequestParser
{
    /**
     * @return Request
     * @throws Exception
     */
    public static function parseRequest() : Request
    {

        $request = new Request();
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
        $path = substr($path, strlen($globalPrefix));

        $protocol = empty($_SERVER["HTTPS"]) ? "http" : "https" ;


        $request->setMethod($_SERVER["REQUEST_METHOD"])
            ->setHeaders($headers)
            ->setQuery($_GET)
            ->setContent($content)
            ->setPath($path)
            ->setUrl("$protocol://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

        return $request;
    }
}