<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Type\Success;

use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Type\SuccessResponse;
use BinSoul\Net\Http\Message\Collection\HeaderCollection;

/**
 * Represents a response with the HTTP status code 200.
 */
class OkResponse extends SuccessResponse
{
    /**
     * Constructs an instance of this class.
     *
     * @param Body                  $body
     * @param HeaderCollection|null $headers
     */
    public function __construct(Body $body, HeaderCollection $headers = null)
    {
        parent::__construct(200, $body, $headers);
    }
}
