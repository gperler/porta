<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Schema;

use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Components;
use Synatos\Porta\Model\Discriminator;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Schema\AnyOfValidator;
use Synatos\Porta\Validator\Schema\NullableValidator;
use Synatos\PortaTest\Validator\ValidationTestBase;

class AnyOfValidatorTest extends ValidationTestBase
{

    /**
     * @throws InvalidSchemaExceptionException
     * @throws InvalidReferenceException
     */
    public function testDiscriminator()
    {
        $openAPI = $this->buildDiscriminatorOpenAPI();

        $referenceResolver = new DefaultReferenceResolver();
        $referenceResolver->setOpenAPI($openAPI);

        $userSchema = new Schema();
        $userSchema->setRef("#/components/schemas/user");

        $customerSchema = new Schema();
        $customerSchema->setRef("#/components/schemas/customer");

        $discriminator = new Discriminator();
        $discriminator->setPropertyName("type");
        $discriminator->setMapping([
            "USER" => "#/components/schemas/user",
            "CUSTOMER" => "#/components/schemas/customer"
        ]);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_OBJECT);
        $schema->setAnyOf([
            $userSchema,
            $customerSchema,
        ]);
        $schema->setDiscriminator($discriminator);


        $anyOfValidator = new AnyOfValidator($referenceResolver);


        $this->testSuccess($anyOfValidator, $schema, [
            "type" => "CUSTOMER",
            "firstName" => "Richard"
        ]);

        $this->testSuccess($anyOfValidator, $schema, [
            "type" => "USER",
            "firstName" => "Richard",
            "role" => "909_Operator"
        ]);

        // test no discriminator value
        $this->testFail($anyOfValidator, $schema, [
            "firstName" => "Richard"
        ], AnyOfValidator::CODE_DISCRIMINATOR_VALUE_MISSING);

        // test no schema for discriminator
        $this->testFail($anyOfValidator, $schema, [
            "type" => "partner",
            "firstName" => "Richard"
        ], AnyOfValidator::CODE_NO_SCHEMA_FOR_DISCRIMINATOR_VALUE);

        // test more than one schema matched
        $schema->setDiscriminator(null);

        //  more than one is ok
        $this->testSuccess($anyOfValidator, $schema, [
            "firstName" => "Richard",
            "role" => "909_Operator"
        ]);


        // test discriminator without mapping
        $discriminator = new Discriminator();
        $discriminator->setPropertyName("_type_");
        $schema->setDiscriminator($discriminator);


        $this->testSuccess($anyOfValidator, $schema, [
            "_type_" => "customer",
            "firstName" => "Richard"
        ]);

        $this->testSuccess($anyOfValidator, $schema, [
            "_type_" => "user",
            "firstName" => "Richard",
            "role" => "k7"
        ]);


        $this->testFail($anyOfValidator, $schema, [
            "_type_" => "external",
            "firstName" => "Richard"
        ], AnyOfValidator::CODE_NO_SCHEMA_FOR_DISCRIMINATOR_VALUE);


        // test schema not matched
        $validationMessageList = $anyOfValidator->validate($schema, [
            "_type_" => "user",
        ], []);

        $this->assertCount(2, $validationMessageList);
        $this->assertSame(NullableValidator::CODE, $validationMessageList[0]->getCode());
        $this->assertSame(NullableValidator::CODE, $validationMessageList[1]->getCode());
    }


    private function buildDiscriminatorOpenAPI()
    {
        $firstName = new Schema();
        $firstName->setType(Schema::TYPE_STRING);

        $role = new Schema();
        $role->setType(Schema::TYPE_STRING);

        $user = new Schema();
        $user->setType(Schema::TYPE_OBJECT);
        $user->setProperties([
            "firstName" => $firstName,
            "role" => $role
        ]);

        $customer = new Schema();
        $customer->setType(Schema::TYPE_OBJECT);
        $customer->setProperties([
            "firstName" => $firstName
        ]);

        $components = new Components();
        $components->setSchemas([
            "user" => $user,
            "customer" => $customer
        ]);

        $openAPI = new OpenAPI();
        $openAPI->setComponents($components);
        return $openAPI;
    }


}