<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\FormatValidatorFactory;

class FormatValidator implements StoppingValidator
{


    /**
     * @param Schema $schema
     * @param $value
     * @param array $propertyPath
     *
     * @return array
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        $format = $schema->getFormat();
        if ($format === null || $value === null) {
            return [];
        }
        $formatValidator = FormatValidatorFactory::getFormatValidatorByFormat($format);
        if ($formatValidator === null) {
            return [];
        }
        return $formatValidator->validate($schema, $value, $propertyPath);
    }


    public function canContinueOnValidationError(): bool
    {
        return true;
    }


}