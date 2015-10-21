<?php

namespace BinSoul\Net\Http\Response\Body\Type;

use BinSoul\Net\MediaType;

/**
 * Represents a body which generates JSON encoded content from provided data.
 */
class JsonBody extends TextBody
{
    /**
     * Constructs an instance of this class.
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(sprintf('Failed to encode data to JSON: %s', json_last_error_msg()));
        }

        parent::__construct($json, new MediaType('application/json; charset=utf-8'));
    }
}
