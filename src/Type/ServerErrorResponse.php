<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Type;

use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Message\Response;
use BinSoul\Net\Http\Response\Body;

/**
 * Represents responses in the HTTP status code range 500 to 599.
 */
class ServerErrorResponse extends Response
{
    use ResponseHelper;

    /**
     * Constructs an instance of this class.
     *
     * @param int                   $status
     * @param Body|null             $body
     * @param HeaderCollection|null $headers
     */
    public function __construct(int $status, Body $body = null, HeaderCollection $headers = null)
    {
        parent::__construct($this->buildBody($body), $status, $this->buildHeaders($headers));
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $result = parent::withStatus($code, $reasonPhrase);

        $this->assertRange($result->getStatusCode(), 500, 599, 'server error');

        return $result;
    }
}
