<?php

namespace App\Routes;

use App\Database\EntityManagerFactory;
use App\Entities\Task;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Tasks
{
    public function getAll(Request $request, Response $response, $args): Response
    {
        $entityManager = (new EntityManagerFactory())->create();

        $task = new Task();
        $task->setTitle('Something');
        $task->setDescription('Some desc');
        $task->setStatus(1);
        $entityManager->persist($task);
        $entityManager->flush();

        $response->getBody()->write(json_encode(['name' => 'adriano']));

        return $response->withHeader('Content-Type', 'application/json');
    }
}