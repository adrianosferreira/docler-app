<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Tasks
{
    private TaskRepository $taskRepository;
    private ResponseBuilderInterface $responseBuilder;

    public function __construct(TaskRepository $taskRepository, ResponseBuilderInterface $responseBuilder)
    {
        $this->taskRepository = $taskRepository;
        $this->responseBuilder = $responseBuilder;
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        return $this->responseBuilder->get($request, $response, $args, $this->taskRepository->getAll());
    }

    public function getById(Request $request, Response $response, $args): Response
    {
        return $this->responseBuilder->get($request, $response, $args, $this->taskRepository->getById($args['id']));
    }
}