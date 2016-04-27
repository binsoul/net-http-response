<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Body;

use BinSoul\Bridge\Http\Message\Stream;
use BinSoul\IO\Stream\Stream as IoStream;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\MediaType;

/**
 * Provides a default implementation of the {@see Body} interface.
 */
class StreamBody extends Stream implements Body
{
    /** @var MediaType */
    protected $mediaType;

    /**
     * Constructs an instance of this class.
     *
     * @param IoStream       $stream
     * @param MediaType|null $mediaType
     */
    public function __construct(IoStream $stream, MediaType $mediaType = null)
    {
        parent::__construct($stream, 'rb+');

        $this->mediaType = $mediaType;
        if ($this->mediaType === null) {
            $this->mediaType = new MediaType('application/octet-stream');
        }
    }

    public function getContentType(): string
    {
        return (string) $this->mediaType;
    }

    public function getContentDisposition()
    {
        return;
    }

    public function getLastModified()
    {
        $stat = $this->stream->getStatistics();

        return isset($stat['mtime']) ? $stat['mtime'] : null;
    }

    public function getEtag(): string
    {
        $defaults = [
            'mtime' => time(),
            'ino' => 0,
            'size' => $this->stream->getSize(),
        ];

        $stat = $this->stream->getStatistics();
        if ($stat !== false) {
            $stat = array_merge($defaults, $stat);
        }

        return md5($stat['mtime'].'-'.$stat['ino'].'-'.$stat['size']);
    }

    public function appendTo(IoStream $stream): bool
    {
        return $this->stream->appendTo($stream);
    }
}
