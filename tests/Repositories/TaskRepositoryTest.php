<?php

namespace App\Tests\Repositories;

use App\Entities\Task;
use App\Repositories\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class TaskRepositoryTest extends TestCase
{

    /**
     * @test
     */
    public function itGetsAllTasks()
    {
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()->getMock();
        $subject       = new TaskRepository($entityManager);

        $expected = [
            [
                'id'          => 1,
                'title'       => 'Some Task',
                'description' => 'Some Description',
                'status'      => 1
            ],
        ];

        $task = new Task();
        $task->setId(1);
        $task->setTitle('Some Task');
        $task->setDescription('Some Description');
        $task->setStatus(1);

        $repository = $this->getMockBuilder(ObjectRepository::class)
            ->onlyMethods(
                ['findAll', 'findOneBy', 'getClassName', 'find', 'findBy']
            )->disableOriginalConstructor()->getMock();
        $repository->method('findBy')->with([])->willReturn([$task]);

        $entityManager->method('getRepository')->willReturn($repository);

        $this->assertEquals($expected, $subject->getAll([]));
    }

    /**
     * @test
     */
    public function itAddsTask()
    {
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()->getMock();
        $subject       = new TaskRepository($entityManager);

        $task = new Task();
        $task->setId(1);
        $task->setTitle('Some Task');
        $task->setDescription('Some Description');
        $task->setStatus(1);

        $entityManager->expects($this->once())->method('persist')->with($task);
        $entityManager->expects($this->once())->method('flush');

        $subject->add($task);
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenTaskCouldntBeDeleted()
    {
        $this->expectException(RuntimeException::class);

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()->getMock();
        $subject       = new TaskRepository($entityManager);

        $repository = $this->getMockBuilder(ObjectRepository::class)
            ->onlyMethods(
                ['findAll', 'findOneBy', 'getClassName', 'find', 'findBy']
            )->disableOriginalConstructor()->getMock();

        $repository->method('findOneBy')->with(['id' => 1])->willReturn(null);
        $entityManager->method('getRepository')->willReturn($repository);

        $subject->delete(1);
    }

    /**
     * @test
     */
    public function itDeletesTheTask()
    {
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()->getMock();
        $subject       = new TaskRepository($entityManager);

        $repository = $this->getMockBuilder(ObjectRepository::class)
            ->onlyMethods(
                ['findAll', 'findOneBy', 'getClassName', 'find', 'findBy']
            )->disableOriginalConstructor()->getMock();

        $task = new Task();
        $task->setId(1);
        $task->setTitle('Some Task');
        $task->setDescription('Some Description');
        $task->setStatus(1);

        $repository->method('findOneBy')->with(['id' => 1])->willReturn($task);
        $entityManager->method('getRepository')->willReturn($repository);
        $entityManager->expects($this->once())->method('remove')->with($task);
        $entityManager->expects($this->once())->method('flush');

        $subject->delete(1);
    }

    /**
     * @test
     */
    public function itThrowsErrorWhenTaskCouldNotBeUpdated()
    {
        $this->expectException(RuntimeException::class);

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()->getMock();
        $subject       = new TaskRepository($entityManager);

        $repository = $this->getMockBuilder(ObjectRepository::class)
            ->onlyMethods(
                ['findAll', 'findOneBy', 'getClassName', 'find', 'findBy']
            )->disableOriginalConstructor()->getMock();

        $task = new Task();
        $task->setId(1);
        $task->setTitle('Some Task');
        $task->setDescription('Some Description');
        $task->setStatus(1);

        $repository->method('findOneBy')->with(['id' => 1])->willReturn(null);
        $entityManager->method('getRepository')->willReturn($repository);

        $subject->update($task);
    }

    /**
     * @test
     */
    public function itUpdatesTheTask()
    {
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()->getMock();
        $subject       = new TaskRepository($entityManager);

        $repository = $this->getMockBuilder(ObjectRepository::class)
            ->onlyMethods(
                ['findAll', 'findOneBy', 'getClassName', 'find', 'findBy']
            )->disableOriginalConstructor()->getMock();

        $taskUpdated = new Task();
        $taskUpdated->setId(1);
        $taskUpdated->setTitle('T: Some Task');
        $taskUpdated->setDescription('T: Some Description');
        $taskUpdated->setStatus(1);

        $task = new Task();
        $task->setId(1);
        $task->setTitle('Some Task');
        $task->setDescription('Some Description');
        $task->setStatus(1);

        $repository->method('findOneBy')->with(['id' => 1])->willReturn($task);
        $entityManager->method('getRepository')->willReturn($repository);
        $entityManager->expects($this->once())->method('flush');

        $subject->update($taskUpdated);
        $this->assertEquals('T: Some Task', $task->getTitle());
        $this->assertEquals('T: Some Description', $task->getDescription());
    }
}
