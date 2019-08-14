<?php

declare(strict_types=1);

namespace Synatos\Porta\Contract;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

interface Validator
{

    /**
     * @param Schema $schema
     * @param $value
     * @param array $propertyPath
     * @return ValidationMessage[]
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Schema $schema, $value, array $propertyPath): array;

}