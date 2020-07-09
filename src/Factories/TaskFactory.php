<?php

namespace App\Factories;

use App\Entities\Task;

class TaskFactory
{

    public function create($args): Task
    {
        $task = new Task();
        $task->setId($args['id'] ?? '');
        $task->setTitle($args['title'] ?? '');
        $task->setDescription($args['description'] ?? '');
        $task->setStatus($args['status'] ?? '');

        return $task;
    }
}