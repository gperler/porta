<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Schema;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\Schema\TypeValidator;
use Synatos\PortaTest\Validator\ValidationTestBase;

class TypeValidatorTest extends ValidationTestBase
{


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testBoolean()
    {
        $typeValidator = new TypeValidator(false);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_BOOLEAN);

        // happy cases
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, true);
        $this->testSuccess($typeValidator, $schema, false);
        $this->testSuccess($typeValidator, $schema, "true");
        $this->testSuccess($typeValidator, $schema, "false");

        $this->testFail($typeValidator, $schema, 1, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 0, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 12.23, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testBooleanStrict()
    {
        $typeValidator = new TypeValidator(true);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_BOOLEAN);

        // happy cases
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, true);
        $this->testSuccess($typeValidator, $schema, false);

        $this->testFail($typeValidator, $schema, 1, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 0, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 12.23, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "true", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "false", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testInt()
    {
        $typeValidator = new TypeValidator(false);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_INTEGER);
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, 0);
        $this->testSuccess($typeValidator, $schema, 1);
        $this->testSuccess($typeValidator, $schema, "0");
        $this->testSuccess($typeValidator, $schema, "1");


        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 0.2, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "0.2", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "0.2", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "y", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testIntStrict()
    {
        $typeValidator = new TypeValidator(true);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_INTEGER);
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, 0);
        $this->testSuccess($typeValidator, $schema, 1);
        $this->testSuccess($typeValidator, $schema, -1);


        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 0.2, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "0.2", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "0.2", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "y", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testNumber()
    {
        $typeValidator = new TypeValidator(false);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_NUMBER);

        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, 0);
        $this->testSuccess($typeValidator, $schema, 1.23);
        $this->testSuccess($typeValidator, $schema, "0");
        $this->testSuccess($typeValidator, $schema, "1.23");

        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "g", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testNumberStrict()
    {
        $typeValidator = new TypeValidator(true);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_NUMBER);

        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, 0);
        $this->testSuccess($typeValidator, $schema, 1.23);
        $this->testSuccess($typeValidator, $schema, -3);


        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "g", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testString()
    {
        $typeValidator = new TypeValidator(false);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_STRING);
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, "0");
        $this->testSuccess($typeValidator, $schema, "1.23");
        $this->testSuccess($typeValidator, $schema, "g");


        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 1.2, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testStringStrict()
    {
        $typeValidator = new TypeValidator(true);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_STRING);
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, "0");
        $this->testSuccess($typeValidator, $schema, "1.23");
        $this->testSuccess($typeValidator, $schema, "g");


        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 1.2, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testArray()
    {
        $typeValidator = new TypeValidator(false);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_ARRAY);
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, []);
        $this->testSuccess($typeValidator, $schema, [0, 4, 2, 4]);


        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 1.2, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "g", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, ["g" => "y"], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testArrayStrict()
    {
        $typeValidator = new TypeValidator(true);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_ARRAY);
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, []);
        $this->testSuccess($typeValidator, $schema, [0, 4, 2, 4]);


        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 1.2, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "g", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, ["g" => "y"], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testObject()
    {
        $typeValidator = new TypeValidator(false);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_OBJECT);
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, []);
        $this->testSuccess($typeValidator, $schema, ["x" => "y"]);


        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 1.2, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "g", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [1, 2, 3], TypeValidator::CODE);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testObjectStrict()
    {
        $typeValidator = new TypeValidator(true);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_OBJECT);
        $this->testSuccess($typeValidator, $schema, null);
        $this->testSuccess($typeValidator, $schema, []);
        $this->testSuccess($typeValidator, $schema, ["x" => "y"]);


        $this->testFail($typeValidator, $schema, true, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, 1.2, TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, "g", TypeValidator::CODE);
        $this->testFail($typeValidator, $schema, [1, 2, 3], TypeValidator::CODE);
    }

}