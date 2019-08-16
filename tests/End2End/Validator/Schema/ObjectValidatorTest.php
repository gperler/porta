<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Validator\Schema;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Schema\ObjectValidator;
use Synatos\PortaTest\End2End\Validator\ValidationTestBase;


class ObjectValidatorTest extends ValidationTestBase
{

    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testRequiredProperty()
    {
        $validator = new ObjectValidator(new DefaultReferenceResolver());

        // schema : [{a:1, b:"", c:""}] array of integers
        $schema = new Schema();
        $schema->setType(Schema::TYPE_OBJECT);
        $schema->setRequired([
            "a",
            "b"
        ]);

        $propertyA = new Schema();
        $propertyA->setType(Schema::TYPE_INTEGER);
        $propertyA->setNullable(true);

        $propertyB = new Schema();
        $propertyB->setType(Schema::TYPE_STRING);
        $propertyB->setNullable(true);

        $propertyC = new Schema();
        $propertyC->setType(Schema::TYPE_STRING);
        $propertyC->setNullable(false);

        $schema->setProperties([
            "a" => $propertyA,
            "b" => $propertyB,
            "c" => $propertyC
        ]);

        $this->testSuccess($validator, $schema, [
            "a" => null,
            "b" => "g",
            "c" => "y"
        ]);


        $this->testFail($validator, $schema, [
            "b" => "g",
            "c" => "y"
        ], ObjectValidator::CODE_REQUIRED);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testMinMaxProperties()
    {
        $validator = new ObjectValidator(new DefaultReferenceResolver());

        $schema = new Schema();
        $schema->setType(Schema::TYPE_OBJECT);

        // test minimum properties
        $schema->setMinProperties(3);
        $this->testSuccess($validator, $schema, [
            "a" => null,
            "b" => "g",
            "c" => "y"
        ]);
        $this->testFail($validator, $schema, [
            "a" => null,
            "b" => "g",
        ], ObjectValidator::CODE_MIN_PROPERTY);

        $schema->setMinProperties(null);


        // test maximum properties
        $schema->setMaxProperties(2);

        $this->testSuccess($validator, $schema, [
            "a" => null,
            "b" => "g",
        ]);
        $this->testFail($validator, $schema, [
            "a" => null,
            "b" => "g",
            "c" => "y"

        ], ObjectValidator::CODE_MAX_PROPERTY);
        $schema->setMinProperties(null);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testObjectRecursion()
    {
        $validator = new ObjectValidator(new DefaultReferenceResolver());

        // schema : [{a:1, b:"", c:""}] array of integers

        $propertyCInner = new Schema();
        $propertyCInner->setType(Schema::TYPE_INTEGER);
        $propertyCInner->setNullable(false);

        $propertyC = new Schema();
        $propertyC->setType(Schema::TYPE_OBJECT);
        $propertyC->setRequired(["x"]);
        $propertyC->setNullable(false);
        $propertyC->setProperties([
            "x" => $propertyCInner
        ]);

        $propertyB = new Schema();
        $propertyB->setType(Schema::TYPE_STRING);
        $propertyB->setNullable(false);

        $propertyA = new Schema();
        $propertyA->setType(Schema::TYPE_INTEGER);
        $propertyA->setNullable(true);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_OBJECT);

        $schema->setProperties([
            "a" => $propertyA,
            "b" => $propertyB,
            "c" => $propertyC
        ]);

        $this->testSuccess($validator, $schema, [
            "a" => 19,
            "b" => "G",
            "c" => [
                "x" => 7
            ]
        ]);
    }
}