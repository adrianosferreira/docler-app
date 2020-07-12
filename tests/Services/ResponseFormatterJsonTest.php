<?php

namespace App\Tests\Services;

use App\Services\Response\ResponseFormatterJson;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;

class ResponseFormatterJsonTest extends TestCase
{

    /**
     * @test
     */
    public function itFormatsResponse()
    {
        $subject = new ResponseFormatterJson();

        $data = ['something'];

        $bodyObject = $this->getMockBuilder(\stdClass::class)->addMethods(
            ['write']
        )->getMock();

        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $bodyObject->method('write')->with(
            json_encode($data, JSON_THROW_ON_ERROR, 512)
        )->willReturn($response);

        $response->method('getBody')->willReturn($bodyObject);

        $response->method('withHeader')->willReturn($response);
        $response->method('withStatus')->with(200)->willReturn($response);

        $this->assertEquals($response, $subject->format($response, $data));
    }

    /**
     * @test
     */
    public function itFormatsErrorMessage()
    {
        $subject = new ResponseFormatterJson();

        $data = ['error' => 'some message'];

        $bodyObject = $this->getMockBuilder(\stdClass::class)->addMethods(
            ['write']
        )->getMock();

        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $bodyObject->method('write')->with(
            json_encode($data, JSON_THROW_ON_ERROR, 512)
        )->willReturn($response);

        $response->method('getBody')->willReturn($bodyObject);

        $response->method('withHeader')->willReturn($response);
        $response->method('withStatus')->with(400)->willReturn($response);

        $exception = $this->getMockBuilder(\stdClass::class)->addMethods(['getMessage'])->disableOriginalConstructor()->getMock();
        $exception->method('getMessage')->willReturn('some message');

        $this->assertEquals($response, $subject->format($response, $data, $exception));
    }

    /**
     * @test
     */
    public function itFormatsNormalMsg()
    {
        $subject = new ResponseFormatterJson();

        $data = 'Some message';

        $bodyObject = $this->getMockBuilder(\stdClass::class)->addMethods(
            ['write']
        )->getMock();

        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $bodyObject->method('write')->with(
            json_encode(['msg' => 'Some message'], JSON_THROW_ON_ERROR, 512)
        )->willReturn($response);

        $response->method('getBody')->willReturn($bodyObject);

        $response->method('withHeader')->willReturn($response);
        $response->method('withStatus')->willReturn($response);

        $this->assertEquals($response, $subject->format($response, $data));
    }

    /**
     * @test
     */
    public function itFormatsResponseResourceCreated()
    {
        $subject = new ResponseFormatterJson();

        $data = ['something'];

        $bodyObject = $this->getMockBuilder(\stdClass::class)->addMethods(
            ['write']
        )->getMock();

        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()->getMock();

        $bodyObject->method('write')->with(
            json_encode($data, JSON_THROW_ON_ERROR, 512)
        )->willReturn($response);

        $response->method('getBody')->willReturn($bodyObject);

        $response->method('withHeader')->willReturn($response);
        $response->method('withStatus')->with(201)->willReturn($response);

        $this->assertEquals($response, $subject->format($response, $data, null, true));
    }
}
