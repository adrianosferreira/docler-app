<?php

namespace App\Controllers;

use App\Entities\Task;
use App\Factories\TaskFactory;
use App\Repositories\TaskRepository;
use App\Services\ResponseFormatterInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Services\Sanitizer;

class TasksController implements ControllerInterface
{
    private TaskRepository $taskRepository;
    private ResponseFormatterInterface $responseFormatter;
    private TaskFactory $taskFactory;

    public function __construct(
        TaskRepository $taskRepository,
        ResponseFormatterInterface $responseFormatter,
        TaskFactory $taskFactory
    ) {
        $this->taskRepository    = $taskRepository;
        $this->responseFormatter = $responseFormatter;
        $this->taskFactory       = $taskFactory;
    }

    public function getAll(
        Request $request,
        Response $response
    ): Response {
        return $this->responseFormatter->format(
            $response,
            $this->taskRepository->getAll()
        );
    }

    public function getById(
        Request $request,
        Response $response,
        $args
    ): Response {
        try {
            $args = Sanitizer::sanitize($args);
            $task = $this->taskRepository->getById($args['id']);
        } catch (\RuntimeException $exception) {
            return $this->responseFormatter->format(
                $response,
                null,
                $exception
            );
        }

        return $this->responseFormatter->format(
            $response,
            $task
        );
    }

    public function add(Request $request, Response $response): Response
    {
        $body           = $request->getParsedBody();
        $body['status'] = Task::STATUS_NEW;

        $task = $this->taskFactory->create(Sanitizer::sanitize($body));
        $this->taskRepository->add($task);

        return $this->responseFormatter->format(
            $response,
            'A task has been created'
        );
    }

    public function delete(
        Request $request,
        Response $response,
        $args
    ): Response {
        try {
            $this->taskRepository->delete($args['id']);
        } catch (\RuntimeException $exception) {
            return $this->responseFormatter->format(
                $response,
                null,
                $exception
            );
        }

        return $this->responseFormatter->format(
            $response,
            "Task {$args['id']} has been deleted"
        );
    }

    public function update(
        Request $request,
        Response $response,
        $args
    ): Response {
        $body       = $request->getParsedBody();
        $body['id'] = $args['id'];

        $task = $this->taskFactory->create(Sanitizer::sanitize($body));

        try {
            $this->taskRepository->update($task);
        } catch (\RuntimeException $exception) {
            return $this->responseFormatter->format(
                $response,
                null,
                $exception
            );
        }

        return $this->responseFormatter->format(
            $response,
            "Task {$args['id']} has been updated"
        );
    }
}
