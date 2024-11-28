<?php

namespace Framework\Routing\Controllers;

use Framework\Context;
use Framework\HTTP\Response;

class NotFoundController extends BaseController
{
    public function index(Context $context) : Response
    {
        return new Response("No route found for \"" . $context->request->getPath() . "\"", 404);
    }
}