<?php

namespace App\Repositories;

use App\Services\SerializerInterface;
use App\Entities\Task;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class TaskRepository
{

    private EntityManagerInterface        $entityManager;
    private SerializerInterface           $responseSerializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $responseSerializer
    ) {
        $this->entityManager      = $entityManager;
        $this->responseSerializer = $responseSerializer;
    }

    public function getAll()
    {
        $res = array_map(
            static function ($task) {
                return [
                    'id'          => $task->getId(),
                    'title'       => $task->getTitle(),
                    'description' => $task->getDescription(),
                    'status'      => $task->getStatus(),
                ];
            },
            $this->getRepository()->findAll()
        );

        return $this->responseSerializer->parse($res);
    }

    public function getById($id)
    {
        $task = $this->getRepository()->findOneBy(['id' => $id]);

        if ( ! $task) {
            return $this->responseSerializer->parse(
                ['msg' => "Task {$id} not found"]
            );
        }

        $res = [
            'id'          => $task->getId(),
            'title'       => $task->getTitle(),
            'description' => $task->getDescription(),
            'status'      => $task->getStatus(),
        ];

        return $this->responseSerializer->parse($res);
    }

    public function add(Task $task)
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->responseSerializer->parse(
            ['msg' => 'Task has been created']
        );
    }

    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Task::class);
    }

    public function delete(int $id)
    {
        $task = $this->getRepository()->findOneBy(['id' => $id]);

        if ( ! $task) {
            return $this->responseSerializer->parse(
                ['msg' => "Task {$id} not found"]
            );
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $this->responseSerializer->parse(
            ['msg' => "The task {$id} has been deleted"]
        );
    }

    public function update(Task $taskUpdated)
    {
        $task = $this->getRepository()->findOneBy(
            ['id' => $taskUpdated->getId()]
        );

        if ( ! $task) {
            return $this->responseSerializer->parse(
                ['msg' => 'A new task has been created']
            );
        }

        if ($taskUpdated->getDescription()) {
            $task->setDescription($taskUpdated->getDescription());
        }

        if ($taskUpdated->getTitle()) {
            $task->setTitle($taskUpdated->getTitle());
        }

        if ($taskUpdated->getStatus()) {
            $task->setStatus($taskUpdated->getStatus());
        }

        $this->entityManager->flush();

        return $this->responseSerializer->parse(
            ['msg' => "The task {$task->getId()} has been updated"]
        );
    }
}