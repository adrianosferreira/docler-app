<?php

namespace App\Tests\Factories;

use App\Entities\Task;
use App\Factories\TaskFactory;
use PHPUnit\Framework\TestCase;

class TaskFactoryTest extends TestCase
{

    /**
     * @test
     */
    public function itCreatesInstanceOfTask() {
        $subject = new TaskFactory();
        $this->assertInstanceOf(Task::class, $subject->create([]));
    }
}
