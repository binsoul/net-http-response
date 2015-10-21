<?php

namespace BinSoul\Net\Http\Response\Type\ClientError;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Type\ClientErrorResponse;

/**
 * Represents a response with the HTTP status code 416.
 */
class InvalidRangeResponse extends ClientErrorResponse
{
    /**
     * Constructs an instance of this class.
     *
     * @param Body                  $body
     * @param HeaderCollection|null $headers
     */
    public function __construct(Body $body = null, HeaderCollection $headers = null)
    {
        parent::__construct(416, $body, $headers);
    }
}
