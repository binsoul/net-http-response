<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Type;

use BinSoul\IO\Stream\Type\NullStream;
use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Message\Response;
use BinSoul\Net\Http\Response\Body\StreamBody;

/**
 * Represents responses in the HTTP status code range 300 to 399.
 */
class RedirectionResponse extends Response
{
    use ResponseHelper;

    /**
     * Constructs an instance of this class.
     *
     * @param int                   $status
     * @param HeaderCollection|null $headers
     */
    public function __construct(int $status, HeaderCollection $headers = null)
    {
        parent::__construct(new StreamBody(new NullStream()), $status, $this->buildHeaders($headers));
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $result = parent::withStatus($code, $reasonPhrase);

        $this->assertRange($result->getStatusCode(), 300, 399, 'redirection');

        return $result;
    }
}
