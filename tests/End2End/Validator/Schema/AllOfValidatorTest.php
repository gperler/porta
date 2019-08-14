<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Schema;

use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Schema\AllOfValidator;
use Synatos\Porta\Validator\Schema\NullableValidator;
use Synatos\PortaTest\Validator\ValidationTestBase;

class AllOfValidatorTest extends ValidationTestBase
{

    /**
     * @throws InvalidSchemaExceptionException
     * @throws InvalidReferenceException
     */
    public function testAllOf()
    {

        $userDB = new Schema();
        $userDB->setType(Schema::TYPE_OBJECT);

        $firstName = new Schema();
        $firstName->setType(Schema::TYPE_STRING);
        $firstName->setMinItems(5);
        $firstName->setNullable(true);

        $lastName = new Schema();
        $lastName->setType(Schema::TYPE_STRING);
        $lastName->setNullable(true);

        $userDB->setProperties([
            "firstName" => $firstName,
            "lastName" => $lastName
        ]);


        $userRequest = new Schema();
        $userRequest->setType(Schema::TYPE_OBJECT);

        $firstName = new Schema();
        $firstName->setType(Schema::TYPE_STRING);
        $firstName->setNullable(false);

        $lastName = new Schema();
        $lastName->setType(Schema::TYPE_STRING);
        $lastName->setNullable(false);

        $password = new Schema();
        $password->setType(Schema::TYPE_STRING);
        $password->setNullable(false);

        $userRequest->setProperties([
            "firstName" => $firstName,
            "lastName" => $lastName,
            "password" => $password,
        ]);


        $allOfSchema = new Schema();
        $allOfSchema->setAllOf([
            $userDB,
            $userRequest,
        ]);


        $allOfValidator = new AllOfValidator(new DefaultReferenceResolver());

        $this->testSuccess($allOfValidator, $allOfSchema, [
            "firstName" => "Peter",
            "lastName" => "Kruder",
            "password" => "k7"
        ]);


        $validationMessageList = $allOfValidator->validate($allOfSchema, [
            "firstName" => "t",
            "lastName" => null,
            "password" => null,
        ], ["user"]);

        $this->assertCount(3, $validationMessageList);

        $this->assertSame(AllOfValidator::CODE_NOT_ALL_OF_ARE_VALID, $validationMessageList[0]->getCode());
        $this->assertSame(NullableValidator::CODE, $validationMessageList[1]->getCode());
        $this->assertSame(NullableValidator::CODE, $validationMessageList[2]->getCode());

    }
}