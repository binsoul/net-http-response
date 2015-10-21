<?php

namespace BinSoul\Test\Net\Http\Response\Body\Decorator;

use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Body\Decorator\Attachment;

class HtmlBodyTest extends \PHPUnit_Framework_TestCase
{
    public function test_uses_attachment_name()
    {
        /** @var Body $body */
        $body = $this->getMock(Body::class);
        $decorator = new Attachment($body, 'foobar');

        $this->assertEquals('attachment; filename="foobar"', $decorator->getContentDisposition());
    }
}
