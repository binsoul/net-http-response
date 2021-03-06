<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Type\ServerError;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Type\ServerErrorResponse;

/**
 * Represents a response with the HTTP status code 503.
 */
class ServiceUnavailableResponse extends ServerErrorResponse
{
    /**
     * Constructs an instance of this class.
     *
     * @param Body                  $body
     * @param HeaderCollection|null $headers
     */
    public function __construct(Body $body = null, HeaderCollection $headers = null)
    {
        parent::__construct(503, $body, $headers);
    }
}
