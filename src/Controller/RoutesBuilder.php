<?php

namespace App\Controller;

use App\Database\EntityManagerFactory;
use App\Repository\TaskRepository;
use App\Repository\TaskRepositoryFactory;
use Slim\App;

class RoutesBuilder
{

    public function run(App $app)
    {
        $tasksController = new Tasks(
            new TaskRepository((new TaskRepositoryFactory())->create(), new SerializerJson()),
            new ResponseBuilderJson()
        );

        $app->get(
            '/tasks',
            [
                $tasksController,
                'getAll'
            ]
        );

        $app->get(
            '/task/{id}',
            [
                $tasksController,
                'getById'
            ]
        );
    }
}