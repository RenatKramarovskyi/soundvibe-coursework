<?php

namespace Framework;

use Framework\HTTP\Request;
use Framework\HTTP\Response;

class Context
{
    public static ?Request $request = null;
    public static ?Response $response = null;

}