<?php

declare(strict_types=1);

namespace Synatos\Porta\Exception;

use Synatos\Porta\Validator\ValidationMessage;

class InvalidSchemaExceptionException extends InvalidOpenAPIDocumentException
{

    /**
     * InvalidSchemaException constructor.
     * @param string|null $message
     * @param array $propertyPath
     */
    public function __construct(?string $message, array $propertyPath)
    {
        $message = ValidationMessage::getMessage($message, $propertyPath);
        parent::__construct($message);
    }
}