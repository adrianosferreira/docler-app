<?php

namespace App\Tests\Controllers;

use App\Controllers\TasksController;
use App\Entities\Task;
use App\Factories\TaskFactory;
use App\Repositories\TaskRepository;
use App\Services\Response\ResponseFormatterInterface;
use PHPUnit\Framework\TestCase;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class TasksControllerTest extends TestCase
{

    /**
     * @test
     */
    public function itReturnsAllTasks()
    {
        $repository        = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter = $this->getMockBuilder(
            ResponseFormatterInterface::class
        )->onlyMethods(['format'])->disableOriginalConstructor()->getMock();
        $taskFactory       = $this->getMockBuilder(TaskFactory::class)
            ->disableOriginalConstructor()->getMock();

        $subject = new TasksController(
            $repository, $responseFormatter, $taskFactory
        );
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();

        $request->method('getQueryParams')->willReturn([]);

        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $task = [
            'id'          => 1,
            'title'       => 'Some Task',
            'description' => 'Some Description',
            'status'      => 1
        ];

        $repository->method('getAll')->with([])->willReturn([$task]);

        $responseOutput = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $responseFormatter->method('format')->with(
            $response,
            [$task]

        )->willReturn($responseOutput);

        $this->assertEquals(
            $responseOutput,
            $subject->getAll($request, $response, [])
        );
    }

    /**
     * @test
     */
    public function itAddsTask()
    {
        $repository        = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter = $this->getMockBuilder(
            ResponseFormatterInterface::class
        )->onlyMethods(['format'])->disableOriginalConstructor()->getMock();
        $taskFactory       = $this->getMockBuilder(TaskFactory::class)
            ->disableOriginalConstructor()->getMock();

        $subject  = new TasksController(
            $repository, $responseFormatter, $taskFactory
        );
        $request  = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $task = new Task();
        $task->setId(1);
        $task->setTitle('Some Task');
        $task->setDescription('Some Description');
        $task->setStatus(1);

        $bodyParsed = [
            'id'          => 1,
            'title'       => 'Some Task',
            'description' => 'Some Description',
            'status'      => 1
        ];

        $request->method('getParsedBody')->willReturn($bodyParsed);

        $taskFactory->method('create')->with($bodyParsed)->willReturn($task);

        $responseOutput = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $repository->expects($this->once())->method('add')->with($task);

        $responseFormatter->method('format')->with(
            $response,
            'A task has been created'

        )->willReturn($responseOutput);

        $this->assertEquals(
            $responseOutput,
            $subject->add($request, $response, ['id' => 1])
        );
    }

    /**
     * @test
     */
    public function itReturnsErrorWhenExceptionIsThrown()
    {
        $repository        = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter = $this->getMockBuilder(
            ResponseFormatterInterface::class
        )->onlyMethods(['format'])->disableOriginalConstructor()->getMock();
        $taskFactory       = $this->getMockBuilder(TaskFactory::class)
            ->disableOriginalConstructor()->getMock();

        $subject  = new TasksController(
            $repository, $responseFormatter, $taskFactory
        );
        $request  = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $task = new Task();
        $task->setId(1);

        $bodyParsed = [
            'id' => 1,
        ];

        $request->method('getParsedBody')->willReturn($bodyParsed);

        $taskFactory->method('create')->with($bodyParsed)->willReturn($task);

        $responseOutput = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $repository->expects($this->never())->method('add')->with($task);

        $responseFormatter->method('format')->willReturn($responseOutput);

        $this->assertEquals(
            $responseOutput,
            $subject->add($request, $response, ['id' => 1])
        );
    }

    /**
     * @test
     */
    public function itDeletesTask()
    {
        $repository        = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter = $this->getMockBuilder(
            ResponseFormatterInterface::class
        )->onlyMethods(['format'])->disableOriginalConstructor()->getMock();
        $taskFactory       = $this->getMockBuilder(TaskFactory::class)
            ->disableOriginalConstructor()->getMock();

        $subject  = new TasksController(
            $repository, $responseFormatter, $taskFactory
        );
        $request  = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $task = [
            'id'          => 1,
            'title'       => 'Some Task',
            'description' => 'Some Description',
            'status'      => 1
        ];

        $repository->method('delete')->with(1)->willReturn($task);

        $responseOutput = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter->method('format')->with(
            $response,
            'Task 1 has been deleted'

        )->willReturn($responseOutput);

        $this->assertEquals(
            $responseOutput,
            $subject->delete($request, $response, ['id' => 1])
        );
    }

    /**
     * @test
     */
    public function itDoesntAddOnExceptionWhenDeleting()
    {
        $repository        = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter = $this->getMockBuilder(
            ResponseFormatterInterface::class
        )->onlyMethods(['format'])->disableOriginalConstructor()->getMock();
        $taskFactory       = $this->getMockBuilder(TaskFactory::class)
            ->disableOriginalConstructor()->getMock();

        $subject  = new TasksController(
            $repository, $responseFormatter, $taskFactory
        );
        $request  = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $exception = new \RuntimeException('Some message');

        $repository->method('delete')->willThrowException($exception);

        $responseOutput = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter->method('format')->with(
            $response,
            null,
            $exception
        )->willReturn($responseOutput);

        $this->assertEquals(
            $responseOutput,
            $subject->delete($request, $response, ['id' => 1])
        );
    }

    /**
     * @test
     */
    public function itUpdatesTask()
    {
        $repository        = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter = $this->getMockBuilder(
            ResponseFormatterInterface::class
        )->onlyMethods(['format'])->disableOriginalConstructor()->getMock();
        $taskFactory       = $this->getMockBuilder(TaskFactory::class)
            ->disableOriginalConstructor()->getMock();

        $subject  = new TasksController(
            $repository, $responseFormatter, $taskFactory
        );
        $request  = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $task = new Task();
        $task->setId(1);
        $task->setTitle('Some Task');
        $task->setDescription('Some Description');
        $task->setStatus(1);

        $bodyParsed = [
            'id'          => 1,
            'title'       => 'Some Task',
            'description' => 'Some Description',
            'status'      => 1
        ];

        $request->method('getParsedBody')->willReturn($bodyParsed);

        $taskFactory->method('create')->with($bodyParsed)->willReturn($task);

        $responseOutput = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $repository->expects($this->once())->method('update')->with($task);

        $responseFormatter->method('format')->with(
            $response,
            'Task 1 has been updated'

        )->willReturn($responseOutput);

        $this->assertEquals(
            $responseOutput,
            $subject->update($request, $response, ['id' => 1])
        );
    }

    /**
     * @test
     */
    public function itDoesntUpdateWhenExceptionIsThrown()
    {
        $repository        = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter = $this->getMockBuilder(
            ResponseFormatterInterface::class
        )->onlyMethods(['format'])->disableOriginalConstructor()->getMock();
        $taskFactory       = $this->getMockBuilder(TaskFactory::class)
            ->disableOriginalConstructor()->getMock();

        $subject  = new TasksController(
            $repository, $responseFormatter, $taskFactory
        );
        $request  = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $task = new Task();
        $task->setId(1);
        $task->setTitle('Some Task');
        $task->setDescription('Some Description');
        $task->setStatus(1);

        $bodyParsed = [
            'id'          => 1,
            'title'       => 'Some Task',
            'description' => 'Some Description',
            'status'      => 3
        ];

        $request->method('getParsedBody')->willReturn($bodyParsed);

        $taskFactory->method('create')->with($bodyParsed)->willReturn($task);

        $responseOutput = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $repository->expects($this->never())->method('update')->with($task);

        $responseFormatter->method('format')->willReturn($responseOutput);

        $this->assertEquals(
            $responseOutput,
            $subject->update($request, $response, ['id' => 1])
        );
    }

    /**
     * @test
     */
    public function itReturnsErrorWhenExceptionIsThrownWhileUpdating()
    {
        $repository        = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()->getMock();
        $responseFormatter = $this->getMockBuilder(
            ResponseFormatterInterface::class
        )->onlyMethods(['format'])->disableOriginalConstructor()->getMock();
        $taskFactory       = $this->getMockBuilder(TaskFactory::class)
            ->disableOriginalConstructor()->getMock();

        $subject  = new TasksController(
            $repository, $responseFormatter, $taskFactory
        );
        $request  = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $task = new Task();
        $task->setId(1);
        $task->setTitle('Some Task');
        $task->setDescription('Some Description');
        $task->setStatus(1);

        $bodyParsed = [
            'id'          => 1,
            'title'       => 'Some Task',
            'description' => 'Some Description',
            'status'      => 1
        ];

        $request->method('getParsedBody')->willReturn($bodyParsed);

        $taskFactory->method('create')->with($bodyParsed)->willReturn($task);

        $responseOutput = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $exception = new \RuntimeException('Some message');

        $repository->expects($this->once())->method('update')
            ->willThrowException($exception);

        $responseFormatter->method('format')->with(
            $response,
            null,
            $exception

        )->willReturn($responseOutput);

        $this->assertEquals(
            $responseOutput,
            $subject->update($request, $response, ['id' => 1])
        );
    }
}
