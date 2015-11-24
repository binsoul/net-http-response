<?php

namespace BinSoul\Net\Http\Response\Type\ServerError;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Type\ServerErrorResponse;

/**
 * Represents a response with the HTTP status code 501.
 */
class NotImplementedResponse extends ServerErrorResponse
{
    /**
     * Constructs an instance of this class.
     *
     * @param Body                  $body
     * @param HeaderCollection|null $headers
     */
    public function __construct(Body $body = null, HeaderCollection $headers = null)
    {
        parent::__construct(501, $body, $headers);
    }
}
