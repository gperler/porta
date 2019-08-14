<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Schema;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\Schema\StringValidator;
use Synatos\PortaTest\Validator\ValidationTestBase;

class StringValidatorTest extends ValidationTestBase
{


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testStringValidator()
    {
        $validator = new StringValidator();

        $schema = new Schema();
        $schema->setType(Schema::TYPE_STRING);

        // test null
        $this->testSuccess($validator, $schema, null);

        // test min length
        $schema->setMinLength(5);
        $this->testSuccess($validator, $schema, "12345");
        $this->testFail($validator, $schema, "12", StringValidator::CODE_MIN_LENGTH);

        // test max length
        $schema->setMinLength(null);
        $schema->setMaxLength(2);
        $this->testSuccess($validator, $schema, "12");
        $this->testFail($validator, $schema, "123", StringValidator::CODE_MAX_LENGTH);

        // test pattern
        $schema->setMaxLength(null);
        $schema->setPattern("[0-9\.\-]");
        $this->testSuccess($validator, $schema, "-12.2333");
        $this->testFail($validator, $schema, "Udjd", StringValidator::CODE_PATTERN);
    }
}