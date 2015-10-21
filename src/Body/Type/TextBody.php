<?php

namespace BinSoul\Net\Http\Response\Body\Type;

use BinSoul\IO\Stream\Type\MemoryStream;
use BinSoul\Net\Http\Response\Body\StreamBody;
use BinSoul\Net\MediaType;

/**
 * Represents a body which returns the content from a provided string.
 *
 * If no media type is provided text/plain is used as default.
 */
class TextBody extends StreamBody
{
    /**
     * Constructs an instance of this class.
     *
     * @param string         $string
     * @param MediaType|null $mediaType
     */
    public function __construct($string, MediaType $mediaType = null)
    {
        if ($mediaType === null) {
            $mediaType = new MediaType('text/plain; charset=utf-8');
        }

        parent::__construct(new MemoryStream($string), $mediaType);
    }
}
