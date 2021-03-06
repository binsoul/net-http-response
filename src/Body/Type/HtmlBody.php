<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Body\Type;

use BinSoul\Net\MediaType;

/**
 * Represents a body which returns HTML content from the provided string.
 *
 * If no charset is provided UTF-8 is used as default.
 */
class HtmlBody extends TextBody
{
    /**
     * Constructs an instance of this class.
     *
     * @param string $string
     * @param string $charset
     */
    public function __construct(string $string, string $charset = 'utf-8')
    {
        $mediaType = new MediaType('text/html; charset='.($charset != '' ? $charset : 'utf-8'));

        parent::__construct($string, $mediaType);
    }
}
