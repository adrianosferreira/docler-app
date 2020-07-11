<?php

namespace App\Controllers;

use App\Entities\Task;
use App\Factories\TaskFactory;
use App\Repositories\TaskRepository;
use App\Services\ResponseFormatterInterface;
use App\Services\TasksValidator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Services\Sanitizer;

class TasksController implements ControllerInterface
{
    private TaskRepository             $taskRepository;
    private ResponseFormatterInterface $responseFormatter;
    private TaskFactory                $taskFactory;

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
        Response $response,
        $args
    ): Response {
        $args = array_merge($args, $request->getQueryParams());

        return $this->responseFormatter->format(
            $response,
            $this->taskRepository->getAll(Sanitizer::sanitize($args))
        );
    }

    public function add(Request $request, Response $response): Response
    {
        $body           = $request->getParsedBody();
        $body['status'] = Task::STATUS_NEW;

        try {
            TasksValidator::isValid($body);
        } catch (\BadMethodCallException $exception) {
            return $this->responseFormatter->format(
                $response,
                null,
                $exception,
            );
        }

        $task = $this->taskFactory->create(Sanitizer::sanitize($body));
        $this->taskRepository->add($task);

        return $this->responseFormatter->format(
            $response,
            'A task has been created',
            null,
            true
        );
    }

    public function delete(
        Request $request,
        Response $response,
        $args
    ): Response {
        try {
            $args = Sanitizer::sanitize($args);
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
        $body       = Sanitizer::sanitize($request->getParsedBody());
        $body['id'] = $args['id'];

        try {
            TasksValidator::isValid($body);
        } catch (\BadMethodCallException $exception) {
            return $this->responseFormatter->format(
                $response,
                null,
                $exception
            );
        }

        $task = $this->taskFactory->create($body);

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
