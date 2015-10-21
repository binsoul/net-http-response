<?php

namespace BinSoul\Test\Net\Http\Response\Body\Type;

use BinSoul\Net\Http\Response\Body\Type\HtmlBody;

class HtmlBodyTest extends \PHPUnit_Framework_TestCase
{
    public function test_default_charset_is_utf8()
    {
        $body = new HtmlBody('foobar');

        $this->assertEquals('text/html; charset=utf-8', $body->getContentType());
    }

    public function test_uses_provided_charset()
    {
        $body = new HtmlBody('foobar', 'utf-16');

        $this->assertEquals('text/html; charset=utf-16', $body->getContentType());
    }
}
