<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class EnumValidator implements StoppingValidator
{
    const EXCEPTION_ENUM_NULL_OR_ARRAY = "enum property must be null or an array";

    const MESSAGE_NOT_IN_ENUM = "Property {PROPERTY}: value '%s' is not in allowed values (%s). ({PATH})";

    const CODE_NOT_IN_ENUM = "enum.notInEnumeration";


    /**
     * @param Schema $schema
     * @param $value
     * @param array $propertyPath
     * @return array
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        $enum = $schema->getEnum();
        if ($value === null || $enum === null) {
            return [];
        }
        if (!is_array($enum)) {
            throw new InvalidSchemaExceptionException(self::EXCEPTION_ENUM_NULL_OR_ARRAY, $propertyPath);
        }

        if (in_array($value, $enum, true)) {
            return [];
        }

        $message = sprintf(self::MESSAGE_NOT_IN_ENUM, $value, implode(",", $enum));
        return [
            new ValidationMessage($message, self::CODE_NOT_IN_ENUM, $propertyPath)
        ];
    }


    /**
     * @return bool
     */
    public function canContinueOnValidationError(): bool
    {
        return true;
    }


}