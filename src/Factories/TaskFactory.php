<?php

namespace App\Factories;

use App\Entities\Task;

class TaskFactory
{

    public function create($args): Task
    {
        $task = new Task();
        $task->setId($args['id'] ?? null);
        $task->setTitle($args['title'] ?? null);
        $task->setDescription($args['description'] ?? null);
        $task->setStatus($args['status'] ?? null);

        return $task;
    }
}
