<?php

namespace BinSoul\Test\Net\Http\Response\Body\Type;

use BinSoul\Net\Http\Response\Body\Type\ResourceBody;
use BinSoul\Net\MediaType;

class ResourceBodyTest extends \PHPUnit_Framework_TestCase
{
    public function test_default_content_type_is_octet_stream()
    {
        $body = new ResourceBody('php://memory');

        $this->assertEquals('application/octet-stream', $body->getContentType());
    }

    public function test_uses_provided_content_type()
    {
        $body = new ResourceBody('php://memory', new MediaType('text/plain'));

        $this->assertEquals('text/plain', $body->getContentType());
    }
}
