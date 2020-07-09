<?php

namespace App\Controllers;

use App\Entities\Task;
use App\Factories\TaskFactory;
use App\Repositories\TaskRepository;
use App\Services\ResponseBuilderInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class TasksController
{
    private TaskRepository           $taskRepository;
    private ResponseBuilderInterface $responseBuilder;
    private TaskFactory              $taskFactory;

    public function __construct(
        TaskRepository $taskRepository,
        ResponseBuilderInterface $responseBuilder,
        TaskFactory $taskFactory
    ) {
        $this->taskRepository = $taskRepository;
        $this->responseBuilder = $responseBuilder;
        $this->taskFactory = $taskFactory;
    }

    public function getAll(
        Request $request,
        Response $response,
        $args
    ): Response {
        return $this->responseBuilder->get(
            $request,
            $response,
            $this->taskRepository->getAll()
        );
    }

    public function getById(
        Request $request,
        Response $response,
        $args
    ): Response {
        return $this->responseBuilder->get(
            $request,
            $response,
            $this->taskRepository->getById(
                $args['id']
            )
        );
    }

    public function add(Request $request, Response $response, $args): Response
    {
        parse_str($request->getBody()->getContents(), $parsedBody);
        $parsedBody['status'] = Task::STATUS_NEW;
        $task = $this->taskFactory->create($parsedBody);
        return $this->responseBuilder->get(
            $request,
            $response,
            $this->taskRepository->add($task)
        );
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        return $this->responseBuilder->get(
            $request,
            $response,
            $this->taskRepository->delete($args['id'])
        );
    }

    public function update(Request $request, Response $response, $args): Response
    {
        parse_str($request->getBody()->getContents(), $parsedBody);
        $parsedBody['id'] = $args['id'];
        $task = $this->taskFactory->create($parsedBody);
        return $this->responseBuilder->get(
            $request,
            $response,
            $this->taskRepository->update($task)
        );
    }
}