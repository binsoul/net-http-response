<?php

namespace BinSoul\Net\Http\Response\Body;

use BinSoul\Bridge\Http\Message\StreamDecorator;
use BinSoul\IO\Stream\Stream;

/**
 * Implements the {@see Body} interface and delegates all methods to the decorated object.
 */
trait BodyDecorator
{
    use StreamDecorator;

    public function getContentType()
    {
        return $this->decoratedObject->getContentType();
    }

    public function getContentDisposition()
    {
        return $this->decoratedObject->getContentDisposition();
    }

    public function getLastModified()
    {
        return $this->decoratedObject->getLastModified();
    }

    public function getEtag()
    {
        return $this->decoratedObject->getEtag();
    }

    public function appendTo(Stream $stream)
    {
        return $this->decoratedObject->appendTo($stream);
    }
}
