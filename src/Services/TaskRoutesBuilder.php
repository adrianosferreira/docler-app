<?php

namespace App\Services;

use App\Controllers\ControllerInterface;
use Slim\App;

class TaskRoutesBuilder implements RoutesBuilderInterface
{

    private App $app;
    private ControllerInterface $controller;

    public function __construct(App $app, ControllerInterface $controller)
    {
        $this->app        = $app;
        $this->controller = $controller;
    }

    public function build()
    {
        $this->app->get(
            '/tasks',
            [
                $this->controller,
                'getAll'
            ]
        );

        $this->app->post(
            '/tasks',
            [
                $this->controller,
                'add'
            ]
        );

        $this->app->get(
            '/task/{id}',
            [
                $this->controller,
                'getById'
            ]
        );

        $this->app->delete(
            '/task/{id}',
            [
                $this->controller,
                'delete'
            ]
        );

        $this->app->put(
            '/task/{id}',
            [
                $this->controller,
                'update'
            ]
        );
    }
}
