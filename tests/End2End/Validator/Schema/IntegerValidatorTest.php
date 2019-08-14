<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Schema;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\Schema\IntegerValidator;
use Synatos\PortaTest\Validator\ValidationTestBase;

class IntegerValidatorTest extends ValidationTestBase
{


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testIntegerValidator()
    {
        $validator = new IntegerValidator();

        $schema = new Schema();
        $schema->setType(Schema::TYPE_INTEGER);

        // test null
        $this->testSuccess($validator, $schema, null);

        // test min
        $schema->setMinimum(5);
        $this->testSuccess($validator, $schema, "6");
        $this->testFail($validator, $schema, 5, IntegerValidator::CODE_MINIMUM);

        $schema->setExclusiveMinimum(true);
        $this->testSuccess($validator, $schema, "5");
        $this->testFail($validator, $schema, 4, IntegerValidator::CODE_MINIMUM);

        $schema->setMinimum(null);
        $schema->setExclusiveMinimum(null);

        // test max
        $schema->setMaximum(10);

        $this->testSuccess($validator, $schema, 9);
        $this->testFail($validator, $schema, 11, IntegerValidator::CODE_MAXIMUM);

        $schema->setExclusiveMaximum(true);
        $this->testSuccess($validator, $schema, "9");
        $this->testFail($validator, $schema, 11, IntegerValidator::CODE_MAXIMUM);

        $schema->setMaximum(null);
        $schema->setExclusiveMaximum(null);


        // test multiple
        $schema->setMultipleOf(2.5);
        $this->testSuccess($validator, $schema, 5);
        $this->testFail($validator, $schema, 5.2, IntegerValidator::CODE_MULTIPLE);
    }
}