<?php

namespace App\Tests\Midleware;

use App\Midleware\ResponseParserMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ResponseParserMiddlewareTest extends TestCase
{

    /**
     * @test
     */
    public function itProcessNonJsonRequests()
    {
        $subject = new ResponseParserMiddleware();

        $handler = $this->getMockBuilder(RequestHandler::class)
            ->disableOriginalConstructor()->getMock();
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();

        $bodyParsed = [
            'id'          => 1,
            'title'       => 'Some Task',
            'description' => 'Some Description',
            'status'      => 1
        ];

        $body = 'id=1&title=Some Task&description=Some Description&status=1';

        $bodyObject = $this->getMockBuilder(\stdClass::class)->addMethods(
            ['getContents']
        )->getMock();
        $bodyObject->method('getContents')->willReturn($body);

        $request->method('getBody')->willReturn($bodyObject);

        $request->method('getHeaderLine')->with('Content-Type')->willReturn(
            'text'
        );

        $request->method('withParsedBody')->with($bodyParsed)->willReturn(
            $request
        );

        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $handler->method('handle')->with($request)->willReturn($response);

        $this->assertEquals($response, $subject->process($request, $handler));
    }

    /**
     * @test
     */
    public function itProcessJsonResponse()
    {
        $subject = new ResponseParserMiddleware();

        $handler = $this->getMockBuilder(RequestHandler::class)
            ->disableOriginalConstructor()->getMock();
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()->getMock();

        $bodyParsed = [
            'id'          => 1,
            'title'       => 'Some Task',
            'description' => 'Some Description',
            'status'      => 1
        ];

        $body = json_encode($bodyParsed, JSON_THROW_ON_ERROR);
        parse_str($body, $bodyWronglyParsed);

        $bodyObject = $this->getMockBuilder(\stdClass::class)->addMethods(
            ['getContents']
        )->getMock();
        $bodyObject->method('getContents')->willReturn($body);

        $request->method('getBody')->willReturn($bodyObject);

        $request->method('getHeaderLine')->with('Content-Type')->willReturn(
            'application/json'
        );

        $request->method('withParsedBody')->withConsecutive(
            [$bodyWronglyParsed],
            [
                json_decode(
                    $body,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                )
            ]
        )->willReturnOnConsecutiveCalls($request, $request);

        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $handler->method('handle')->with($request)->willReturn($response);

        $this->assertEquals($response, $subject->process($request, $handler));
    }
}
