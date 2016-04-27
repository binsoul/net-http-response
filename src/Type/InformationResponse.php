<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Type;

use BinSoul\Bridge\Http\Message\Stream;
use BinSoul\IO\Stream\Type\NullStream;
use BinSoul\Net\Http\Message\Collection\HeaderCollection;
use BinSoul\Net\Http\Message\Response;

/**
 * Represents responses in the HTTP status code range 100 to 199.
 */
class InformationResponse extends Response
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
        parent::__construct(new Stream(new NullStream(), 'r'), $status, $this->buildHeaders($headers));
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $result = parent::withStatus($code, $reasonPhrase);

        $this->assertRange($result->getStatusCode(), 100, 199, 'information');

        return $result;
    }
}
