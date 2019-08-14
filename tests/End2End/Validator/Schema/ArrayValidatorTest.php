<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Schema;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Schema\ArrayValidator;
use Synatos\Porta\Validator\Schema\NullableValidator;
use Synatos\Porta\Validator\Schema\TypeValidator;
use Synatos\PortaTest\Validator\ValidationTestBase;

class ArrayValidatorTest extends ValidationTestBase
{

    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testMinMaxItems()
    {
        $validator = new ArrayValidator(new DefaultReferenceResolver());

        // schema : [1,2,3] array of integers
        $schema = new Schema();
        $schema->setType(Schema::TYPE_ARRAY);

        $itemSchema = new Schema();
        $itemSchema->setType(Schema::TYPE_INTEGER);
        $schema->setItems($itemSchema);

        // test minimum items
        $schema->setMinItems(2);
        $this->testSuccess($validator, $schema, [1, 2]);
        $this->testFail($validator, $schema, [1], ArrayValidator::CODE_MIN_ITEMS);
        $schema->setMinItems(null);

        $schema->setMaxItems(3);
        $this->testSuccess($validator, $schema, [1, 2, 3]);
        $this->testFail($validator, $schema, [1, 2, 3, 4], ArrayValidator::CODE_MAX_ITEMS);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testUniqueItems()
    {
        $validator = new ArrayValidator(new DefaultReferenceResolver());

        // schema : [1,2,3] array of integers
        $schema = new Schema();
        $schema->setType(Schema::TYPE_ARRAY);
        $schema->setUniqueItems(true);

        $itemSchema = new Schema();
        $itemSchema->setType(Schema::TYPE_INTEGER);
        $schema->setItems($itemSchema);

        $this->testSuccess($validator, $schema, [1, 2, 3]);
        $this->testFail($validator, $schema, [1, 1, 2], ArrayValidator::CODE_UNIQUE_ITEMS);
    }


    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testItemsSchema()
    {
        $validator = new ArrayValidator(new DefaultReferenceResolver());

        // schema : [1,2,3] array of integers
        $schema = new Schema();
        $schema->setType(Schema::TYPE_ARRAY);

        $itemSchema = new Schema();
        $itemSchema->setType(Schema::TYPE_INTEGER);
        $itemSchema->setNullable(false);

        $schema->setItems($itemSchema);

        $this->testSuccess($validator, $schema, [1, 2, 3]);


        $this->testFail($validator, $schema, [1, 2, null], NullableValidator::CODE);
        $this->testFail($validator, $schema, ["a"], TypeValidator::CODE);
    }


}