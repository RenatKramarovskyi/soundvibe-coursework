<?php

namespace Framework;

use Framework\HTTP\JsonResponse;
use Framework\HTTP\RequestParser;
use Framework\HTTP\Response;

class Core
{
    public function handle() :string
    {
        Context::$request = RequestParser::parseRequest();

            Context::$response = new JsonResponse(["some field 1" => "some value 1",
                "some field 2" => "some value 2",
                "some field 3" => "some value 3",], 200);

        return (string)Context::$response;
    }
}