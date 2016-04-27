<?php

namespace BinSoul\Test\Net\Http\Response\Type;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Type\InformationResponse;

class InformationResponseTest extends \PHPUnit_Framework_TestCase
{
    public function test_has_sane_defaults()
    {
        $response = new InformationResponse(100);

        $this->assertEquals(100, $response->getStatusCode());
        $this->assertInstanceOf(Body::class, $response->getBody());
        $this->assertNotEmpty($response->getHeaders());
    }

    public function test_uses_given_headers()
    {
        $headers = $this->getMock(HeaderCollection::class);
        $headers->expects($this->any())->method('all')->willReturn(['foo' => 'bar']);

        $response = new InformationResponse(100, $headers);

        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }

    public function test_changes_status()
    {
        $response = new InformationResponse(100);

        $this->assertEquals(199, $response->withStatus(199)->getStatusCode());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_throws_exception_for_invalid_status()
    {
        $response = new InformationResponse(100);
        $response->withStatus(99);
    }
}
