<?php

declare(strict_types=1);

namespace Synatos\Porta\Exception;

class InvalidReferenceException extends \Exception
{
    const REFERENCE_COULD_NOT_BE_RESOLVED = "Reference '%s' could not be resolved";


    /**
     * InvalidReferenceException constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

}