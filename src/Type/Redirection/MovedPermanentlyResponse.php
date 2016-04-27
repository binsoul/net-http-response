<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Type\Redirection;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Response\Type\RedirectionResponse;
use Psr\Http\Message\UriInterface;

/**
 * Represents a response with the HTTP status code 301.
 */
class MovedPermanentlyResponse extends RedirectionResponse
{
    /**
     * Constructs an instance of this class.
     *
     * @param UriInterface          $uri
     * @param HeaderCollection|null $headers
     */
    public function __construct(UriInterface $uri, HeaderCollection $headers = null)
    {
        $headers = clone $this->buildHeaders($headers);
        $headers->set('Location', (string) $uri);

        parent::__construct(301, $headers);
    }
}
