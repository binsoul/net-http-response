<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Type\Redirection;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Type\RedirectionResponse;

/**
 * Represents a response with the HTTP status code 304.
 */
class NotModifiedResponse extends RedirectionResponse
{
    /**
     * Constructs an instance of this class.
     *
     * @param HeaderCollection|null $headers
     */
    public function __construct(HeaderCollection $headers = null)
    {
        parent::__construct(304, $headers);
    }
}
