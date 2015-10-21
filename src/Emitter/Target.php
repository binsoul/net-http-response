<?php

namespace BinSoul\Net\Http\Response\Emitter;

use Psr\Http\Message\StreamInterface;

/**
 * Provides concrete methods to output headers and body for a concrete environment.
 */
interface Target
{
    /**
     * Starts the output.
     */
    public function beginOutput();

    /**
     * Finishes the output.
     */
    public function endOutput();

    /**
     * Outputs a response header line.
     *
     * @param string $header
     */
    public function outputHeader($header);

    /**
     * Outputs the response body.
     *
     * @param StreamInterface $body
     */
    public function outputBody(StreamInterface $body);
}
