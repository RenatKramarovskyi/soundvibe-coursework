<?php

namespace Framework;

use Framework\HTTP\JsonResponse;
use Framework\HTTP\RequestParser;
use Framework\HTTP\Response;
use Framework\ORM\Connection;
use Framework\Routing\Router;

class Core
{
    public function handle() :string
    {
        Context::$request = RequestParser::parseRequest();

        Context::$connection = new Connection();
        Context::$connection->connect();

        Router::execute();

        return (string)Context::$response;
    }
}