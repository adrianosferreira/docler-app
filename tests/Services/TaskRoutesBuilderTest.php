<?php

namespace App\Tests\Services;

use App\Controllers\ControllerInterface;
use App\Services\TaskRoutesBuilder;
use PHPUnit\Framework\TestCase;
use Slim\App;

class TaskRoutesBuilderTest extends TestCase
{

    /**
     * @test
     */
    public function itBuildsRoutes()
    {
        $app        = $this->getMockBuilder(App::class)
            ->disableOriginalConstructor()->getMock();
        $controller = $this->getMockBuilder(ControllerInterface::class)
            ->addMethods(['get'])->disableOriginalConstructor()->getMock();
        $subject    = new TaskRoutesBuilder($app, $controller);

        $app->expects($this->exactly(2))->method('get')->withConsecutive(
            [
                '/tasks',
                [
                    $controller,
                    'getAll'
                ]
            ],
            [
                '/task/{id}',
                [
                    $controller,
                    'getAll'
                ]
            ],
            );

        $app->expects($this->once())->method('post')->with(
            '/tasks',
            [
                $controller,
                'add'
            ]
        );

        $app->expects($this->once())->method('delete')->with(
            '/task/{id}',
            [
                $controller,
                'delete'
            ]
        );

        $app->expects($this->once())->method('put')->with(
            '/task/{id}',
            [
                $controller,
                'update'
            ]
        );

        $subject->build();
    }
}
