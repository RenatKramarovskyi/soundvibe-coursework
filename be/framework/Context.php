<?php

namespace Framework;

use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\ORM\Connection;

class Context
{
    public ?Request $request = null;
    public ?Response $response = null;
    public ?Connection $connection = null;
}