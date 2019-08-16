<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Validator\Schema;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\Schema\NullableValidator;
use Synatos\PortaTest\End2End\Validator\ValidationTestBase;

class NullableValidatorTest extends ValidationTestBase
{

    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testNullable()
    {
        $validator = new NullableValidator();

        $schema = new Schema();
        $schema->setNullable(true);

        $this->testSuccess($validator, $schema, null);
        $this->testSuccess($validator, $schema, "!");

        $schema->setNullable(false);
        $this->testSuccess($validator, $schema, "!");
        $this->testFail($validator, $schema, null, NullableValidator::CODE);
    }


}