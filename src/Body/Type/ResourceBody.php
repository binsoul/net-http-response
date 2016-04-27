<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Body\Type;

use BinSoul\IO\Stream\Type\ResourceStream;
use BinSoul\Net\Http\Response\Body\StreamBody;
use BinSoul\Net\MediaType;

/**
 * Represents a body which returns the content from a resource.
 *
 * If no media type is provided and the given resource is a file this class tries to detect the media type. Otherwise
 * application/octet-stream is used.
 */
class ResourceBody extends StreamBody
{
    /**
     * Constructs an instance of this class.
     *
     * @param string         $uri
     * @param MediaType|null $mediaType
     */
    public function __construct(string $uri, MediaType $mediaType = null)
    {
        if ($mediaType === null) {
            $mediaType = MediaType::fromFile($uri, true);
        }

        parent::__construct(new ResourceStream($uri), $mediaType);
    }
}
