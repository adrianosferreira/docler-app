<?php

use App\Controllers\TasksController;
use App\Database\EntityManagerFactory;
use App\Factories\TaskFactory;
use App\Midleware\ErrorHandler;
use App\Repositories\TaskRepository;
use App\Services\ResponseFormatterJson;
use App\Services\RoutesBuilderInterface;
use App\Services\TaskRoutesBuilder;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$routesBuilders = [
    new TaskRoutesBuilder(
        $app, new TasksController(
                new TaskRepository((new EntityManagerFactory())->create()),
                new ResponseFormatterJson(),
                new TaskFactory(),
            )
    )
];

array_map(static function (RoutesBuilderInterface $routeBuilder) {
    return $routeBuilder->build();
}, $routesBuilders);

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(
    [(new ErrorHandler($app->getResponseFactory())), 'get']
);

$app->run();