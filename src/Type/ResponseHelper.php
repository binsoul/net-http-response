<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Type;

use BinSoul\IO\Stream\Type\MemoryStream;
use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Body\StreamBody;

/**
 * Provides common methods for response types.
 */
trait ResponseHelper
{
    /**
     * @param int $timeStamp
     *
     * @return string
     */
    protected function formatTimestamp(int $timeStamp): string
    {
        $time = new \DateTime();
        $time->setTimestamp($timeStamp);
        $time->setTimezone(new \DateTimeZone('UTC'));

        return $time->format('D, d M Y H:i:s').' GMT';
    }

    /**
     * @param HeaderCollection|null $headers
     *
     * @return HeaderCollection
     */
    protected function buildHeaders(HeaderCollection $headers = null)
    {
        if ($headers !== null) {
            return $headers;
        }

        $now = new \DateTime(null, new \DateTimeZone('UTC'));
        $today = new \DateTime(null, new \DateTimeZone('UTC'));
        $today->setTime(0, 0, 0);

        return new HeaderCollection(
            [
                'Date' => $now->format('D, d M Y H:i:s').' GMT',
                'Last-Modified' => $now->format('D, d M Y H:i:s').' GMT',
                'Expires' => $today->format('D, d M Y H:i:s').' GMT',
                'Cache-Control' => 'no-cache,no-store,must-revalidate,proxy-revalidate,max-age=0',
                'Pragma' => 'no-cache',
            ]
        );
    }

    /**
     * @param Body|null $body
     *
     * @return Body
     */
    protected function buildBody(Body $body = null)
    {
        if ($body !== null) {
            return $body;
        }

        return new StreamBody(new MemoryStream());
    }

    /**
     * @param int    $statusCode
     * @param int    $minimalCode
     * @param int    $maximalCode
     * @param string $responseType
     */
    protected function assertRange(int $statusCode, int $minimalCode, int $maximalCode, string $responseType)
    {
        if ($statusCode < $minimalCode || $statusCode > $maximalCode) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid status code "%d" for response type "%s".',
                    $statusCode,
                    $responseType
                )
            );
        }
    }
}
