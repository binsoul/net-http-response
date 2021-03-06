<?php

declare (strict_types = 1);

namespace BinSoul\Net\Http\Response\Body\Decorator;

use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Body\BodyDecorator;

/**
 * Declares the decorated body as an attachment which should be downloaded instead of displayed.
 */
class Attachment implements Body
{
    use BodyDecorator;

    /** @var string */
    private $attachmentName;

    /**
     * Constructs an instance of this class.
     *
     * @param Body   $decorated
     * @param string $attachmentName
     */
    public function __construct(Body $decorated, string $attachmentName)
    {
        $this->setDecoratedObject($decorated);
        $this->attachmentName = $attachmentName;
    }

    public function getContentDisposition(): string
    {
        return 'attachment; filename="'.$this->attachmentName.'"';
    }
}
