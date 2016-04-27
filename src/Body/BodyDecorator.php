<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Body;

use BinSoul\Bridge\Http\Message\StreamDecorator;
use BinSoul\IO\Stream\Stream;

/**
 * Implements the {@see Body} interface and delegates all methods to the decorated object.
 */
trait BodyDecorator
{
    use StreamDecorator;

    public function getContentType(): string
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

    public function getEtag(): string
    {
        return $this->decoratedObject->getEtag();
    }

    public function appendTo(Stream $stream): bool
    {
        return $this->decoratedObject->appendTo($stream);
    }
}
