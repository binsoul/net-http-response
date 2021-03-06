<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Emitter\Target;

use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Emitter\Target;
use Psr\Http\Message\StreamInterface;

/**
 * Captures all output and provides methods to access the captured data.
 */
class CaptureTarget implements Target
{
    /** @var string[] */
    private $headers = [];
    /** @var string */
    private $body = '';

    public function beginOutput()
    {
    }

    public function endOutput()
    {
    }

    public function outputHeader(string $header)
    {
        $this->headers[] = $header;
    }

    public function outputBody(StreamInterface $body)
    {
        $this->body = (string) $body;
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $headerLine
     *
     * @return bool
     */
    public function hasHeader($headerLine): bool
    {
        foreach ($this->headers as $header) {
            if ($header == $headerLine) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
