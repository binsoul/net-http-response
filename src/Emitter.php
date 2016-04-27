<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response;

use BinSoul\Net\Http\Response\Emitter\Target;
use Psr\Http\Message\ResponseInterface;

/**
 * Outputs a response.
 */
interface Emitter
{
    /**
     * Outputs the response using the given target.
     *
     * @param ResponseInterface $response
     * @param Target            $target
     */
    public function emit(ResponseInterface $response, Target $target);
}
