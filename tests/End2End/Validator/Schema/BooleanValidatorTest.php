<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Schema;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\Schema\BooleanValidator;
use Synatos\PortaTest\Validator\ValidationTestBase;

class BooleanValidatorTest extends ValidationTestBase
{

    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testBoolean()
    {
        $validator = new BooleanValidator();

        $schema = new Schema();
        $schema->setType(Schema::TYPE_BOOLEAN);

        $this->testSuccess($validator, $schema, null);
        $this->testSuccess($validator, $schema, true);
        $this->testSuccess($validator, $schema, false);
        $this->testSuccess($validator, $schema, "true");
        $this->testSuccess($validator, $schema, "false");

        $this->testFail($validator, $schema, 1, BooleanValidator::CODE);
        $this->testFail($validator, $schema, 1.1, BooleanValidator::CODE);
        $this->testFail($validator, $schema, "asd", BooleanValidator::CODE);
    }


}