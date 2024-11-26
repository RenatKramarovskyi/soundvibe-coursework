<?php

namespace Framework;

use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\ORM\Connection;

class Context
{
    public static ?Request $request = null;
    public static ?Response $response = null;
    public static ?Connection $connection = null;
}