<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Format;

use Synatos\Porta\Contract\Validator;
use Synatos\Porta\Model\Schema;

class FormatEmailValidator implements Validator
{
    const FORMAT_NAME = "email";


    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        return [];
    }

}