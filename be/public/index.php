<?php

use Framework\Core;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->Load();


$core = new Core();

$core->use(Framework\HTTP\Cors::class);

echo $core->handle();

