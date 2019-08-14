<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class NullableValidator implements StoppingValidator
{

    const MESSAGE = "The field {PROPERTY} is not nullable ({PATH})";

    const CODE = "nullable.invalidValue";


    /**
     * @param Schema $schema
     * @param $value
     * @param array $propertyPath
     *
     * @return array
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        if ($schema->isNullable() || $value !== null) {
            return [];
        }
        return [
            new ValidationMessage(self::MESSAGE, self::CODE, $propertyPath)
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