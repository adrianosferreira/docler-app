<?php

use App\Controllers\TasksController;
use App\Database\EntityManagerFactory;
use App\Factories\TaskFactory;
use App\Midleware\ErrorHandlerMiddleware;
use App\Midleware\ResponseParserMiddleware;
use App\Repositories\TaskRepository;
use App\Services\Response\ResponseFormatterJson;
use App\Services\Routes\RoutesBuilderInterface;
use App\Services\Routes\TaskRoutesBuilder;
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
$app->add(ResponseParserMiddleware::class);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(
    [(new ErrorHandlerMiddleware($app->getResponseFactory())), 'get']
);

$app->run();