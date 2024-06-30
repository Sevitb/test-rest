<?php

define('BASE_DIR', dirname(__DIR__));

require BASE_DIR . '/vendor/autoload.php';

$app = new Src\Application(new Src\Routing\Router);
$response = $app->handle(Src\Http\Requests\Request::init());
$response->send();