<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Operation;

use Codeception\Test\Unit;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\HttpRequest;
use Synatos\Porta\Model\Parameter;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Operation\RequestParameterValidator;
use Synatos\Porta\Validator\Schema\NullableValidator;
use Synatos\Porta\Validator\Schema\TypeValidator;

class RequestParameterValidatorTest extends Unit
{

    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function testParameterInHeader()
    {
        $parameterValidator = new RequestParameterValidator(new DefaultReferenceResolver());

        $stringType = new Schema();
        $stringType->setType(Schema::TYPE_STRING);

        $parameter = new Parameter();
        $parameter->setName("AuthToken");
        $parameter->setIn(Parameter::IN_HEADER);
        $parameter->setSchema($stringType);

        // test success
        $httpRequest = new HttpRequest("/test", "post", [
            "AuthToken" => "0x33",
        ]);

        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(0, $validationMessageList);

        // test failure
        $httpRequest = new HttpRequest("/test", "post");
        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(NullableValidator::CODE, $validationMessageList[0]->getCode());


        // test integer in header
        $intType = new Schema();
        $intType->setType(Schema::TYPE_INTEGER);

        $parameter = new Parameter();
        $parameter->setName("UserId");
        $parameter->setIn(Parameter::IN_HEADER);
        $parameter->setSchema($intType);

        // integer in header : success
        $httpRequest = new HttpRequest("/test", "post", [
            "UserId" => "238293",
        ]);

        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(0, $validationMessageList);


        // integer in header :  failure
        $httpRequest = new HttpRequest("/test", "post", [
            "UserId" => "238293d",
        ]);

        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(TypeValidator::CODE, $validationMessageList[0]->getCode());
    }


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function testParameterInPathParameter()
    {
        $parameterValidator = new RequestParameterValidator(new DefaultReferenceResolver());

        $stringType = new Schema();
        $stringType->setType(Schema::TYPE_STRING);

        $parameter = new Parameter();
        $parameter->setName("userId");
        $parameter->setIn(Parameter::IN_PATH);
        $parameter->setSchema($stringType);

        // test success
        $httpRequest = new HttpRequest("/test", "post", [], ["userId" => "0x3d4a"]);

        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(0, $validationMessageList);

        // test failure
        $httpRequest = new HttpRequest("/test", "post", [], ["userIdNew" => "0x3d4a"]);

        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(NullableValidator::CODE, $validationMessageList[0]->getCode());
    }


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function testParameterInQuery()
    {
        $parameterValidator = new RequestParameterValidator(new DefaultReferenceResolver());

        $booleanType = new Schema();
        $booleanType->setType(Schema::TYPE_BOOLEAN);

        $parameter = new Parameter();
        $parameter->setName("enableFast");
        $parameter->setIn(Parameter::IN_QUERY);
        $parameter->setSchema($booleanType);

        // test success
        $httpRequest = new HttpRequest("/test", "post", [], [], ["enableFast" => "true"]);

        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(0, $validationMessageList);

        // test failure
        $httpRequest = new HttpRequest("/test", "post", [], [], ["enableFast" => "1"]);

        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(TypeValidator::CODE, $validationMessageList[0]->getCode());
    }


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function testParameterInCookie()
    {
        $parameterValidator = new RequestParameterValidator(new DefaultReferenceResolver());

        $numberType = new Schema();
        $numberType->setType(Schema::TYPE_NUMBER);

        $parameter = new Parameter();
        $parameter->setName("m");
        $parameter->setIn(Parameter::IN_COOKIE);
        $parameter->setSchema($numberType);

        // test success
        $httpRequest = new HttpRequest("/test", "post", [
            "Cookie" => "zp34=mosaic; m=328232"
        ], [], []);

        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(0, $validationMessageList);

        // test failure
        $httpRequest = new HttpRequest("/test", "post", [
            "Cookie" => "zp34=mosaic; m=3282***32"
        ], [], []);

        $validationMessageList = $parameterValidator->validateParameter($parameter, $httpRequest);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(TypeValidator::CODE, $validationMessageList[0]->getCode());
    }


}