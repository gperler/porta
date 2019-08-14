<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Operation;

use Codeception\Test\Unit;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\ContentType;
use Synatos\Porta\Http\HttpHeader;
use Synatos\Porta\Http\HttpRequest;
use Synatos\Porta\Model\MediaType;
use Synatos\Porta\Model\RequestBody;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Operation\RequestBodyValidator;
use Synatos\Porta\Validator\Schema\NullableValidator;
use Synatos\Porta\Validator\Schema\TypeValidator;

class RequestBodyValidatorTest extends Unit
{


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function testRequestBodyValidator()
    {
        $requestBodyValidator = new RequestBodyValidator(new DefaultReferenceResolver());

        $string = new Schema();
        $string->setType(Schema::TYPE_STRING);

        $number = new Schema();
        $number->setType(Schema::TYPE_NUMBER);

        $bool = new Schema();
        $bool->setType(Schema::TYPE_BOOLEAN);

        $object = new Schema();
        $object->setType(Schema::TYPE_OBJECT);
        $object->setProperties([
            "string" => $string,
            "bool" => $bool,
            "number" => $number
        ]);

        $main = new Schema();
        $main->setType(Schema::TYPE_OBJECT);
        $main->setProperties([
            "string" => $string,
            "number" => $number,
            "bool" => $bool,
            "object" => $object
        ]);
        $mediaType = new MediaType();
        $mediaType->setSchema($main);

        $requestBody = new RequestBody();
        $requestBody->setRequired(true);
        $requestBody->setContent([
            ContentType::APPLICATION_JSON => $mediaType
        ]);

        $httpRequest = $this->createHttpRequest([
            "string" => "set",
            "bool" => true,
            "number" => 77,
            "object" => [
                "string" => "string",
                "number" => 19,
                "bool" => false,
            ]
        ]);


        // test success
        $validationMessageList = $requestBodyValidator->validateRequestBody($requestBody, $httpRequest);
        $this->assertCount(0, $validationMessageList);


        // test required body
        $httpRequest = $this->createHttpRequest();
        $validationMessageList = $requestBodyValidator->validateRequestBody($requestBody, $httpRequest);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(RequestBodyValidator::CODE_REQUEST_BODY_REQUIRED, $validationMessageList[0]->getCode());

        // test schema
        $httpRequest = $this->createHttpRequest([
            "string" => 7,
            "bool" => 19,
            "object" => [
                1, 2, 3
            ]
        ]);

        $validationMessageList = $requestBodyValidator->validateRequestBody($requestBody, $httpRequest);

        $this->assertCount(4, $validationMessageList);

        $this->assertSame(TypeValidator::CODE, $validationMessageList[0]->getCode());
        $this->assertSame(NullableValidator::CODE, $validationMessageList[1]->getCode());
        $this->assertSame(TypeValidator::CODE, $validationMessageList[2]->getCode());
        $this->assertSame(TypeValidator::CODE, $validationMessageList[3]->getCode());
    }


    /**
     * @param array|null $data
     *
     * @return HttpRequest
     */
    private function createHttpRequest(array $data = null): HttpRequest
    {
        $content = $data !== null ? json_encode($data) : null;
        return new HttpRequest("/", "post", [
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON
        ], [], [], $content);
    }

}