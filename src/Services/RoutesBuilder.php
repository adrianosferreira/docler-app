<?php

namespace App\Services;

use App\Controllers\TasksController;
use App\Factories\TaskFactory;
use App\Database\EntityManagerFactory;
use App\Repositories\TaskRepository;
use Slim\App;

class RoutesBuilder
{

    public function run(App $app)
    {
        $tasksController = new TasksController(
            new TaskRepository((new EntityManagerFactory())->create(), new SerializerJson()),
            new ResponseBuilderJson(),
            new TaskFactory()
        );

        $app->get(
            '/tasks',
            [
                $tasksController,
                'getAll'
            ]
        );

        $app->post(
            '/tasks',
            [
                $tasksController,
                'add'
            ]
        );

        $app->get(
            '/task/{id}',
            [
                $tasksController,
                'getById'
            ]
        );

        $app->delete(
            '/task/{id}',
            [
                $tasksController,
                'delete'
            ]
        );

        $app->put(
            '/task/{id}',
            [
                $tasksController,
                'update'
            ]
        );
    }
}