<?php

namespace BinSoul\Test\Net\Http\Response\Type;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Type\ClientErrorResponse;

class ClientErrorResponseTest extends \PHPUnit_Framework_TestCase
{
    public function test_has_sane_defaults()
    {
        $response = new ClientErrorResponse(400);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertInstanceOf(Body::class, $response->getBody());
        $this->assertNotEmpty($response->getHeaders());
    }

    public function test_uses_given_body()
    {
        $body = $this->getMock(Body::class);
        $response = new ClientErrorResponse(400, $body);

        $this->assertEquals($body, $response->getBody());
    }

    public function test_uses_given_headers()
    {
        $headers = $this->getMock(HeaderCollection::class);
        $headers->expects($this->any())->method('all')->willReturn(['foo' => 'bar']);

        $response = new ClientErrorResponse(400, null, $headers);

        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }

    public function test_changes_status()
    {
        $response = new ClientErrorResponse(400);

        $this->assertEquals(499, $response->withStatus(499)->getStatusCode());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_throws_exception_for_invalid_status()
    {
        $response = new ClientErrorResponse(400);
        $response->withStatus(99);
    }
}
