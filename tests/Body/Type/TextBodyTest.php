<?php

namespace BinSoul\Test\Net\Http\Response\Body\Type;

use BinSoul\Net\Http\Response\Body\Type\TextBody;
use BinSoul\Net\MediaType;

class TextBodyTest extends \PHPUnit_Framework_TestCase
{
    public function test_default_content_type_is_text_plain()
    {
        $body = new TextBody('foobar');

        $this->assertEquals('text/plain; charset=utf-8', $body->getContentType());
    }

    public function test_uses_provided_content_type()
    {
        $body = new TextBody('foobar', new MediaType('text/x-php'));

        $this->assertEquals('text/x-php', $body->getContentType());
    }
}
