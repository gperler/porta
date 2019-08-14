<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class BooleanValidator implements StoppingValidator
{

    const MESSAGE = "Property {PROPERTY}: value '%s' is not valid boolean. ({PATH})";

    const CODE = "boolean.invalidValue";


    /**
     * @param Schema $schema
     * @param $value
     * @param array $propertyPath
     * @return ValidationMessage[]
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        // values in parameter or header will be strings
        if (is_string($value) && ($value === "true" || $value === "false" || $value === null)) {
            return [];
        }
        if ($value === null || $value === false || $value === true) {
            return [];
        }
        $message = sprintf(self::MESSAGE, $value);
        return [
            new ValidationMessage($message, self::CODE, $propertyPath)
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