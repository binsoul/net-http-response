<?php

namespace BinSoul\Net\Http\Response\Emitter;

use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Emitter;
use Psr\Http\Message\ResponseInterface;

/**
 * Provides a default implementation of the {@see Emitter} interface.
 */
class DefaultEmitter implements Emitter
{
    public function emit(ResponseInterface $response, Target $target)
    {
        $target->beginOutput();

        $this->emitStatusLine($response, $target);
        $this->emitHeaders($response, $target);
        $this->emitBody($response, $target);

        $target->endOutput();
    }

    /**
     * Emits the status line with protocol version, status code and reason phrase.
     *
     * @param Target            $target
     * @param ResponseInterface $response
     */
    private function emitStatusLine(ResponseInterface $response, Target $target)
    {
        $reasonPhrase = $response->getReasonPhrase();
        $target->outputHeader(
            sprintf(
                'HTTP/%s %d%s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                ($reasonPhrase ? ' '.$reasonPhrase : '')
            )
        );
    }

    /**
     * Emits the response headers.
     *
     * @param Target            $target
     * @param ResponseInterface $response
     */
    private function emitHeaders(ResponseInterface $response, Target $target)
    {
        $headers = $response->getHeaders();
        $body = $response->getBody();

        if ($this->isAllowedToEmitBody($response)) {
            // set Content-Type if none is given
            if ($response->getHeaderLine('Content-Type') == '') {
                $contentType = '';

                if ($body instanceof Body) {
                    $contentType = $body->getContentType();
                }

                if ($contentType == '') {
                    $contentType = 'application/octet-stream';
                }

                $headers['Content-Type'] = [$contentType];
            }

            // set Content-Length if none is given and Transfer-Encoding is empty or "identity"
            $contentLength = $response->getHeaderLine('Content-Length');
            $transferEncoding = $response->getHeaderLine('Transfer-Encoding');
            if ($contentLength == '' &&
                ($transferEncoding == '' || $transferEncoding == 'identity') &&
                $body->getSize() !== null
            ) {
                $headers['Content-Length'] = [$body->getSize()];
            }

            if ($body instanceof Body) {
                if ($body->getContentDisposition() !== null && $response->getHeaderLine('Content-Disposition') == '') {
                    $headers['Content-Disposition'] = [$body->getContentDisposition()];
                }

                if ($response->getHeaderLine('ETag') == '') {
                    $eTag = $body->getEtag();
                    if ($eTag != '') {
                        $headers['ETag'] = [$body->getEtag()];
                    }
                }

                if ($body->getLastModified() !== null) {
                    $headers['Last-Modified'] = [$this->formatTimestamp($body->getLastModified())];
                }
            }
        }

        foreach ($headers as $header => $values) {
            if (!$this->isAllowedToEmitHeader($header, $response)) {
                continue;
            }

            $target->outputHeader(sprintf('%s: %s', $header, implode(',', $values)));
        }
    }

    /**
     * Emits the response body.
     *
     * @param Target            $target
     * @param ResponseInterface $response
     */
    private function emitBody(ResponseInterface $response, Target $target)
    {
        if (!$this->isAllowedToEmitBody($response)) {
            return;
        }

        $target->outputBody($response->getBody());
    }

    /**
     * Checks if the response header is allowed for the given request.
     *
     * @param string            $header
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function isAllowedToEmitHeader($header, ResponseInterface $response)
    {
        $entityHeaders = [
            'allow',
            'content-encoding',
            'content-language',
            'content-length',
            'content-location',
            'content-md5',
            'content-range',
            'content-type',
            'expires',
            'last-modified',
        ];

        $statusCode = $response->getStatusCode();
        if ($statusCode == 304 && in_array(strtolower($header), $entityHeaders)) {
            return false;
        }

        return true;
    }

    /**
     * Returns true if the response can include a body.
     *
     * All 1xx (informational), 204 (no content), and 304 (not modified) responses MUST NOT include a message-body.
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function isAllowedToEmitBody(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode < 200 || $statusCode == 204 || $statusCode == 304) {
            return false;
        }

        return true;
    }

    /**
     * Formats a timestamp as a header value.
     *
     * @param int $timeStamp
     *
     * @return string
     */
    private function formatTimestamp($timeStamp)
    {
        $time = new \DateTime();
        $time->setTimestamp($timeStamp);
        $time->setTimezone(new \DateTimeZone('UTC'));

        return $time->format('D, d M Y H:i:s').' GMT';
    }
}
