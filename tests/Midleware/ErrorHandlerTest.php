<?php

namespace App\Tests\Midleware;

use App\Midleware\ErrorHandlerMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorHandlerTest extends TestCase
{

    /**
     * @test
     */
    public function itReturnsnotFoundError()
    {
        $responseFactory = $this->getMockBuilder(
            ResponseFactoryInterface::class
        )->disableOriginalConstructor()->getMock();
        $subject         = new ErrorHandlerMiddleware($responseFactory);
        $response        = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()->getMock();
        $body            = $this->getMockBuilder(\stdClass::class)->addMethods(
            ['write']
        )->disableOriginalConstructor()->getMock();

        $body->expects($this->once())->method('write')->with(
                json_encode(
                    ['error' => 'Some description'],
                    JSON_THROW_ON_ERROR,
                    512
                )
            );

        $response->method('getBody')->willReturn($body);
        $response->method('withStatus')->willReturn($response);

        $responseFactory->method('createResponse')->willReturn($response);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()->getMock();

        $exception = $this->getMockBuilder(\stdClass::class)->addMethods(
            ['getCode', 'getMessage']
        )->disableOriginalConstructor()->getMock();
        $exception->method('getCode')->willReturn(404);
        $exception->method('getMessage')->willReturn('Some description');

        $this->assertEquals($response, $subject->get($request, $exception));
    }
}
