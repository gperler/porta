<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Validator\Schema;

use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Schema\AdditionalPropertiesValidator;
use Synatos\Porta\Validator\Schema\NullableValidator;
use Synatos\Porta\Validator\Schema\TypeValidator;
use Synatos\PortaTest\End2End\Validator\ValidationTestBase;

class AdditionalPropertiesValidatorTest extends ValidationTestBase
{
    /**
     * @throws InvalidSchemaExceptionException
     */
    public function testAdditionalProperties()
    {

        $additionalPropertiesSchema = new Schema();
        $additionalPropertiesSchema->setType(Schema::TYPE_STRING);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_OBJECT);
        $schema->setAdditionalProperties($additionalPropertiesSchema);

        $additionalPropertiesValidator = new AdditionalPropertiesValidator(new DefaultReferenceResolver());

        $this->testSuccess($additionalPropertiesValidator, $schema, []);
        $this->testSuccess($additionalPropertiesValidator, $schema, [
            "x" => "y",
            "y" => "z"
        ]);

        $this->testFail($additionalPropertiesValidator, $schema, [
            "x" => 7
        ], TypeValidator::CODE);

        $this->testFail($additionalPropertiesValidator, $schema, [
            "x" => null
        ], NullableValidator::CODE);
    }
}