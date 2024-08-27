<?php

namespace Framework\Routing\Controllers;

use Framework\Context;
use Framework\HTTP\Response;

class NotFoundController extends BaseController
{
    public function index() : Response
    {
        return new Response("No route found for \"" . Context::$request->getPath() . "\"", 404);
    }
}