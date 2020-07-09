<?php

namespace App\Tests\Services;

use App\Services\ResponseFormatterJson;
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

        $this->assertEquals($response, $subject->format($response, $data));
    }
}
