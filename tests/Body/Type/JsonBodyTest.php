<?php

namespace BinSoul\Test\Net\Http\Response\Body\Type;

use BinSoul\Net\Http\Response\Body\Type\JsonBody;

class JsonBodyTest extends \PHPUnit_Framework_TestCase
{
    public function test_has_correct_media_type()
    {
        $body = new JsonBody(['foo' => 'bar']);

        $this->assertEquals('application/json; charset=utf-8', $body->getContentType());
    }

    public function test_serializes_correctly()
    {
        $body = new JsonBody('foobar');
        $this->assertEquals('"foobar"', (string) $body);

        $body = new JsonBody(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], json_decode((string) $body, true));

        $body = new JsonBody(['ööö' => 'äää']);
        $this->assertEquals(['ööö' => 'äää'], json_decode((string) $body, true));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_throws_exception_for_invalid_data()
    {
        $body = new JsonBody(fopen('php://memory', 'r'));
    }
}
