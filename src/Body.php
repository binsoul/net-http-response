<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response;

use BinSoul\IO\Stream\Stream;
use Psr\Http\Message\StreamInterface;

/**
 * Provides additional information about a stream.
 */
interface Body extends StreamInterface
{
    /**
     * Returns the content type.
     *
     * @return string
     */
    public function getContentType(): string;

    /**
     * Returns the content disposition.
     *
     * @return string|null
     */
    public function getContentDisposition();

    /**
     * Returns the last modification time as an unix timestamp.
     *
     * @return int|null
     */
    public function getLastModified();

    /**
     * Returns an unique hash of the stream.
     *
     * @return string
     */
    public function getEtag(): string;

    /**
     * Appends all data to the given stream.
     *
     * @param Stream $stream stream to append to
     *
     * @return bool
     */
    public function appendTo(Stream $stream): bool;
}
