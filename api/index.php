<?php

require_once("App/Core/headers.php");
require_once("vendor/autoload.php");
ini_set('display_errors', 'on');

use App\Core\Router;

$router = new Router();
$router->run();
