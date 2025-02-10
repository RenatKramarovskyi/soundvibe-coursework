<?php

use Framework\Core;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->Load();

$dotenvLocal = Dotenv\Dotenv::createImmutable(dirname(__DIR__), ".env.local");
$dotenvLocal->Load();

$core = new Core();

$core->use(Framework\HTTP\Cors::class);
$core->use(\App\Middleware\JWT::class);

echo $core->handle();


