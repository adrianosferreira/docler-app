<?php

namespace App\Repository;

use App\Database\EntityManagerFactory;
use App\Entities\Task;

class TaskRepositoryFactory
{
    public function create() {
        $entityManager = (new EntityManagerFactory())->create();
        return $entityManager->getRepository(Task::class);
    }
}