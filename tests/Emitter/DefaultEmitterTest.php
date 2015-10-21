<?php

namespace BinSoul\Test\Net\Http\Response\Emitter;

use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Emitter\Target\CaptureTarget;
use BinSoul\Net\Http\Response\Emitter\DefaultEmitter;
use Psr\Http\Message\ResponseInterface;

class DefaultEmitterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function buildBody()
    {
        $body = $this->getMock(Body::class, get_class_methods(Body::class));
        $body->expects($this->any())->method('__toString')->willReturn('foobar');
        $body->expects($this->any())->method('getContents')->willReturn('foobar');
        $body->expects($this->any())->method('getSize')->willReturn(12345);
        $body->expects($this->any())->method('getEtag')->willReturn(12345);

        return $body;
    }

    /**
     * @param $status
     * @param $phrase
     * @param $headers
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function buildResponse($status, $phrase, $headers)
    {
        $response = $this->getMock(ResponseInterface::class);
        $response->expects($this->any())->method('getStatusCode')->willReturn($status);
        $response->expects($this->any())->method('getReasonPhrase')->willReturn($phrase);
        $response->expects($this->any())->method('getProtocolVersion')->willReturn('1.1');
        $response->expects($this->any())->method('getHeaders')->willReturn($headers);

        return $response;
    }

    public function test_200_ok()
    {
        $body = $this->buildBody();

        $response = $this->buildResponse(200, 'Ok', ['foo' => ['bar']]);
        $response->expects($this->any())->method('getBody')->willReturn($body);

        $emitter = new DefaultEmitter();
        $target = new CaptureTarget();

        /* @var ResponseInterface $response */
        $emitter->emit($response, $target);

        $this->assertEquals('HTTP/1.1 200 Ok', $target->getHeaders()[0]);
        $this->assertEquals('foo: bar', $target->getHeaders()[1]);
        $this->assertEquals('foobar', $target->getBody());
    }

    public function test_304_not_modfied()
    {
        $body = $this->buildBody();

        $response = $this->buildResponse(304, 'Not Modified', ['foo' => ['bar'], 'Content-Length' => [1]]);
        $response->expects($this->any())->method('getBody')->willReturn($body);

        $emitter = new DefaultEmitter();
        $target = new CaptureTarget();

        /* @var ResponseInterface $response */
        $emitter->emit($response, $target);

        $this->assertEquals('HTTP/1.1 304 Not Modified', $target->getHeaders()[0]);
        $this->assertEquals('foo: bar', $target->getHeaders()[1]);
        $this->assertNull($target->getBody());
        $this->assertFalse($target->hasHeader('Content-Length: 1'));
    }

    public function test_body_content_disposition()
    {
        $body = $this->buildBody();
        $body->expects($this->any())->method('getContentDisposition')->willReturn('attachment');

        $response = $this->buildResponse(206, 'Partial Content', []);
        $response->expects($this->any())->method('getBody')->willReturn($body);

        $emitter = new DefaultEmitter();
        $target = new CaptureTarget();

        /* @var ResponseInterface $response */
        $emitter->emit($response, $target);

        $this->assertEquals('foobar', $target->getBody());
        $this->assertTrue($target->hasHeader('Content-Disposition: attachment'));
    }

    public function test_body_last_modified()
    {
        $body = $this->buildBody();
        $body->expects($this->any())->method('getLastModified')->willReturn(251773323);

        $response = $this->buildResponse(200, 'Ok', []);
        $response->expects($this->any())->method('getBody')->willReturn($body);

        $emitter = new DefaultEmitter();
        $target = new CaptureTarget();

        /* @var ResponseInterface $response */
        $emitter->emit($response, $target);

        $this->assertEquals('foobar', $target->getBody());
        $this->assertTrue($target->hasHeader('Last-Modified: Sat, 24 Dec 1977 01:02:03 GMT'));
    }
}
