<?php

namespace BinSoul\Test\Net\Http\Response\Type;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Type\ServerErrorResponse;

class ServerErrorResponseTest extends \PHPUnit_Framework_TestCase
{
    public function test_has_sane_defaults()
    {
        $response = new ServerErrorResponse(500);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertInstanceOf(Body::class, $response->getBody());
        $this->assertNotEmpty($response->getHeaders());
    }

    public function test_uses_given_body()
    {
        $body = $this->getMock(Body::class);
        $response = new ServerErrorResponse(500, $body);

        $this->assertEquals($body, $response->getBody());
    }

    public function test_uses_given_headers()
    {
        $headers = $this->getMock(HeaderCollection::class);
        $headers->expects($this->any())->method('all')->willReturn(['foo' => 'bar']);

        $response = new ServerErrorResponse(500, null, $headers);

        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }

    public function test_changes_status()
    {
        $response = new ServerErrorResponse(500);

        $this->assertEquals(599, $response->withStatus(599)->getStatusCode());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_throws_exception_for_invalid_status()
    {
        $response = new ServerErrorResponse(500);
        $response->withStatus(99);
    }
}
