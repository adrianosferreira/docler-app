<?php

namespace App\Repositories;

use App\Entities\Task;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class TaskRepository
{

    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
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

        return $res;
    }

    public function getById($id)
    {
        $task = $this->getRepository()->findOneBy(['id' => $id]);

        if ( ! $task) {
            throw new \RuntimeException("Task {$id} not found");
        }

        return [
            'id'          => $task->getId(),
            'title'       => $task->getTitle(),
            'description' => $task->getDescription(),
            'status'      => $task->getStatus(),
        ];
    }

    public function add(Task $task)
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function delete(int $id)
    {
        $task = $this->getRepository()->findOneBy(['id' => $id]);

        if ( ! $task) {
            throw new \RuntimeException("Task {$id} not found");
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function update(Task $taskUpdated)
    {
        $task = $this->getRepository()->findOneBy(
            ['id' => $taskUpdated->getId()]
        );

        if ( ! $task) {
            throw new \RuntimeException(
                "Task {$taskUpdated->getId()} not found"
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
    }

    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Task::class);
    }
}