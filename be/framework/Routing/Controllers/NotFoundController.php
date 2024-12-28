<?php

namespace Framework\Routing\Controllers;

use Framework\HTTP\Request;
use Framework\HTTP\Response;

class NotFoundController extends BaseController
{
    public function index(Request $request) : Response
    {
        return new Response("No route found for \"" . $request->getPath() . "\"", 404);
    }
}