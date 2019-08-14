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
use Synatos\Porta\Model\Operation;
use Synatos\Porta\Model\Parameter;
use Synatos\Porta\Model\RequestBody;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Operation\RequestBodyValidator;
use Synatos\Porta\Validator\Operation\RequestValidator;
use Synatos\Porta\Validator\Schema\NullableValidator;
use Synatos\Porta\Validator\Schema\TypeValidator;

class RequestValidatorTest extends Unit
{

    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function testRequestValidator()
    {
        $requestValidator = new RequestValidator(new DefaultReferenceResolver());

        $operation = $this->createOperation();

        $httpRequest = $this->createHttpRequest([
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON,
            "SpecialHeader" => "123929"
        ], [
            "userId" => "myUserId"
        ], [
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
        $validationMessageList = $requestValidator->validate($operation, $httpRequest);
        $this->assertCount(0, $validationMessageList);

        $httpRequest = $this->createHttpRequest([
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON,
            "SpecialHeader" => "NotANumber"
        ], [], [
            "string" => "set",
            "bool" => "true",
            "number" => 77,
        ]);
        $validationMessageList = $requestValidator->validate($operation, $httpRequest);
        $this->assertCount(4, $validationMessageList);

        $this->assertSame(NullableValidator::CODE, $validationMessageList[0]->getCode());
        $this->assertSame(TypeValidator::CODE, $validationMessageList[1]->getCode());
        $this->assertSame(TypeValidator::CODE, $validationMessageList[2]->getCode());
        $this->assertSame(NullableValidator::CODE, $validationMessageList[3]->getCode());
    }


    /**
     * @param array $header
     * @param array $query
     * @param array|null $data
     *
     * @return HttpRequest
     */
    private function createHttpRequest(array $header, array $query, array $data = null): HttpRequest
    {
        $content = $data !== null ? json_encode($data) : null;
        return new HttpRequest("/", "post", $header, [], $query, $content);
    }


    /**
     * @return Operation
     */
    private function createOperation(): Operation
    {
        $operation = new Operation();
        $operation->setParameters($this->createParameter());
        $operation->setRequestBody($this->createRequestBody());
        return $operation;
    }


    /**
     * @return Parameter[]
     */
    private function createParameter(): array
    {
        $stringType = new Schema();
        $stringType->setType(Schema::TYPE_STRING);

        $parameter1 = new Parameter();
        $parameter1->setName("userId");
        $parameter1->setIn(Parameter::IN_QUERY);
        $parameter1->setSchema($stringType);

        $intType = new Schema();
        $intType->setType(Schema::TYPE_INTEGER);

        $parameter2 = new Parameter();
        $parameter2->setName("SpecialHeader");
        $parameter2->setIn((Parameter::IN_HEADER));
        $parameter2->setSchema($intType);

        return [
            $parameter1,
            $parameter2
        ];
    }


    /**
     * @return RequestBody
     */
    private function createRequestBody(): RequestBody
    {
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
        return $requestBody;
    }

}