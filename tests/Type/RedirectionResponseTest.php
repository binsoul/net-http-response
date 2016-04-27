<?php

namespace BinSoul\Test\Net\Http\Response\Type;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Type\RedirectionResponse;

class RedirectionResponseTest extends \PHPUnit_Framework_TestCase
{
    public function test_has_sane_defaults()
    {
        $response = new RedirectionResponse(300);

        $this->assertEquals(300, $response->getStatusCode());
        $this->assertInstanceOf(Body::class, $response->getBody());
        $this->assertNotEmpty($response->getHeaders());
    }

    public function test_uses_given_headers()
    {
        $headers = $this->getMock(HeaderCollection::class);
        $headers->expects($this->any())->method('all')->willReturn(['foo' => 'bar']);

        $response = new RedirectionResponse(300, $headers);

        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }

    public function test_changes_status()
    {
        $response = new RedirectionResponse(300);

        $this->assertEquals(399, $response->withStatus(399)->getStatusCode());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_throws_exception_for_invalid_status()
    {
        $response = new RedirectionResponse(300);
        $response->withStatus(99);
    }
}
