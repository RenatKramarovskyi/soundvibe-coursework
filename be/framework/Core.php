<?php

namespace Framework;

use Framework\HTTP\JsonResponse;
use Framework\HTTP\RequestParser;
use Framework\HTTP\Response;
use Framework\Routing\Router;

class Core
{
    public function handle() :string
    {
        Context::$request = RequestParser::parseRequest();
        Router::execute();
        return (string)Context::$response;
    }
}