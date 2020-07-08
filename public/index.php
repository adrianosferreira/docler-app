<?php

use App\Routes\RoutesBuilder;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
(new RoutesBuilder())->run($app);
$app->run();