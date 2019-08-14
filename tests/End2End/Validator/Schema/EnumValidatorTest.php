<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Schema;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\Schema\EnumValidator;
use Synatos\PortaTest\Validator\ValidationTestBase;

class EnumValidatorTest extends ValidationTestBase
{


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testBooleanEnum()
    {
        $validator = new EnumValidator();

        $schema = new Schema();
        $schema->setEnum([true, false]);

        $this->testSuccess($validator, $schema, null);
        $this->testSuccess($validator, $schema, true);
        $this->testSuccess($validator, $schema, false);

        $this->testFail($validator, $schema, 1, EnumValidator::CODE_NOT_IN_ENUM);
        $this->testFail($validator, $schema, 1.1, EnumValidator::CODE_NOT_IN_ENUM);
        $this->testFail($validator, $schema, "asd", EnumValidator::CODE_NOT_IN_ENUM);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testIntEnum()
    {
        $validator = new EnumValidator();

        $schema = new Schema();
        $schema->setEnum([1, 2, 7]);

        $this->testSuccess($validator, $schema, null);
        $this->testSuccess($validator, $schema, 1);
        $this->testSuccess($validator, $schema, 2);

        $this->testFail($validator, $schema, 4, EnumValidator::CODE_NOT_IN_ENUM);
        $this->testFail($validator, $schema, 1.1, EnumValidator::CODE_NOT_IN_ENUM);
        $this->testFail($validator, $schema, "asd", EnumValidator::CODE_NOT_IN_ENUM);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testStringEnum()
    {
        $validator = new EnumValidator();

        $schema = new Schema();
        $schema->setEnum(['S_OK', 'S_ERR']);

        $this->testSuccess($validator, $schema, null);
        $this->testSuccess($validator, $schema, "S_OK");

        $this->testFail($validator, $schema, 4, EnumValidator::CODE_NOT_IN_ENUM);
        $this->testFail($validator, $schema, 1.1, EnumValidator::CODE_NOT_IN_ENUM);
        $this->testFail($validator, $schema, "asd", EnumValidator::CODE_NOT_IN_ENUM);
    }


}