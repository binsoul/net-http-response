<?php

namespace BinSoul\Test\Net\Http\Response\Body;

use BinSoul\IO\Stream\Stream;
use BinSoul\Net\Http\Response\Body\StreamBody;
use BinSoul\Net\MediaType;

class StreamBodyTest extends \PHPUnit_Framework_TestCase
{
    public function test_default_content_type_is_octet_stream()
    {
        /** @var Stream $stream */
        $stream = $this->getMock(Stream::class);
        $body = new StreamBody($stream);

        $this->assertEquals('application/octet-stream', $body->getContentType());
    }

    public function test_uses_provided_content_type()
    {
        /** @var Stream $stream */
        $stream = $this->getMock(Stream::class);
        $body = new StreamBody($stream, new MediaType('text/plain'));

        $this->assertEquals('text/plain', $body->getContentType());
    }

    public function test_default_content_disposition_is_null()
    {
        /** @var Stream $stream */
        $stream = $this->getMock(Stream::class);
        $body = new StreamBody($stream);

        $this->assertNull($body->getContentDisposition());
    }

    public function test_default_last_modified_is_null()
    {
        $stream = $this->getMock(Stream::class);
        $stream->expects($this->any())->method('getStatistics')->willReturn([]);

        /* @var Stream $stream */
        $body = new StreamBody($stream);

        $this->assertNull($body->getLastModified());
    }

    public function test_last_modified_uses_mtime()
    {
        $stream = $this->getMock(Stream::class);
        $stream->expects($this->any())->method('getStatistics')->willReturn(['mtime' => 123456]);

        /* @var Stream $stream */
        $body = new StreamBody($stream);

        $this->assertEquals(123456, $body->getLastModified());
    }

    public function test_can_generate_etag_if_stat_fails()
    {
        $stream = $this->getMock(Stream::class);
        $stream->expects($this->any())->method('getStatistics')->willReturn(false);

        /* @var Stream $stream */
        $body = new StreamBody($stream);

        $this->assertNotEmpty($body->getEtag());
    }

    public function test_can_generate_etag_if_stat_is_invalid()
    {
        $stream = $this->getMock(Stream::class);
        $stream->expects($this->any())->method('getStatistics')->willReturn(['foo' => 'bar']);

        /* @var Stream $stream */
        $body = new StreamBody($stream);

        $this->assertNotEmpty($body->getEtag());
    }

    public function test_can_generate_etag_if_stat_is_valid()
    {
        $stat = [
            'mtime' => 12345,
            'ino' => 12345,
            'size' => 12345,
        ];

        $stream = $this->getMock(Stream::class);
        $stream->expects($this->any())->method('getStatistics')->willReturn($stat);

        /* @var Stream $stream */
        $body = new StreamBody($stream);

        $this->assertNotEmpty($body->getEtag());
    }

    public function test_append_to_uses_stream()
    {
        $stream = $this->getMock(Stream::class);
        $stream->expects($this->any())->method('appendTo')->willReturn(true);

        /* @var Stream $stream */
        $body = new StreamBody($stream);

        $this->assertTrue($body->appendTo($stream));
    }
}
