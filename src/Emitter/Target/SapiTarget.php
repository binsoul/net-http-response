<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Emitter\Target;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Type\PhpOutputStream;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Emitter\Target;
use Psr\Http\Message\StreamInterface;

/**
 * Emits a response for a PHP SAPI environment.
 *
 * The status line and headers are emitted via the header() function and the body content via
 * the output buffer or the PHP output stream.
 */
class SapiTarget implements Target
{
    /** @var int */
    private $maxBufferLevel;
    /** @var bool */
    private $finishResponse;
    /** @var bool */
    private $useOutputStream;

    /**
     * Constructs an instance of this class.
     *
     * @param bool $finishResponse
     * @param int  $maxBufferLevel
     * @param bool $useOutputStream
     */
    public function __construct(bool $finishResponse = true, int $maxBufferLevel = 0, bool $useOutputStream = true)
    {
        $this->finishResponse = $finishResponse;
        $this->maxBufferLevel = $maxBufferLevel;
        $this->useOutputStream = $useOutputStream;
    }

    public function beginOutput()
    {
        if (headers_sent($file, $line)) {
            throw new \RuntimeException(sprintf('Headers are already sent in file "%s" on line %d.', $file, $line));
        }

        do {
            if (ob_get_level() <= $this->maxBufferLevel) {
                break;
            }

            $flushed = @ob_end_clean();
            if (!$flushed && ob_get_level() > 0) {
                throw new \RuntimeException('Unable to clear output buffers.');
            }
        } while ($flushed);
    }

    public function endOutput()
    {
        if (!$this->finishResponse) {
            return;
        }

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            ob_start(
                function () {
                    return '';
                }
            );
        }
    }

    public function outputHeader(string $header)
    {
        header($header, true);
    }

    public function outputBody(StreamInterface $body)
    {
        if ($this->useOutputStream) {
            $output = new PhpOutputStream();
            $output->open(new AccessMode('w'));

            if ($body instanceof Body) {
                $body->appendTo($output);
            } else {
                $output->write((string) $body);
            }

            $output->close();
        } else {
            echo $body;
        }
    }
}
