<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Operation;

use Codeception\Test\Unit;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\ContentType;
use Synatos\Porta\Http\HttpHeader;
use Synatos\Porta\Http\HttpResponse;
use Synatos\Porta\Model\MediaType;
use Synatos\Porta\Model\Operation;
use Synatos\Porta\Model\Response;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Operation\ResponseValidator;
use Synatos\Porta\Validator\Schema\NullableValidator;

class ResponseValidatorTest extends Unit
{

    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function testResponseValidator()
    {
        $responseValidator = new ResponseValidator(new DefaultReferenceResolver());
        $operation = $this->createOperation();

        // test happy 200
        $httpResponse = new HttpResponse(200, [
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON
        ], [
            "int" => 200
        ]);

        $validationMessageList = $responseValidator->validateResponse($operation, $httpResponse);
        $this->assertCount(0, $validationMessageList);

        // test happy 4XX
        $httpResponse = new HttpResponse(432, [
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON
        ], [
            "int4xx" => 432
        ]);

        $validationMessageList = $responseValidator->validateResponse($operation, $httpResponse);
        $this->assertCount(0, $validationMessageList);


        // test 200 not ok
        $httpResponse = new HttpResponse(200, [
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON
        ], null);

        $validationMessageList = $responseValidator->validateResponse($operation, $httpResponse);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(NullableValidator::CODE, $validationMessageList[0]->getCode());


        // test 200 not ok
        $httpResponse = new HttpResponse(432, [
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON
        ], null);

        $validationMessageList = $responseValidator->validateResponse($operation, $httpResponse);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(NullableValidator::CODE, $validationMessageList[0]->getCode());
    }


    private function createOperation(): Operation
    {
        $intProperty = new Schema();
        $intProperty->setType(Schema::TYPE_INTEGER);

        $schema200 = new Schema();
        $schema200->setType(Schema::TYPE_OBJECT);
        $schema200->setProperties([
            "int" => $intProperty
        ]);

        $schema4XX = new Schema();
        $schema4XX->setType(Schema::TYPE_OBJECT);
        $schema4XX->setProperties([
            "int4xx" => $intProperty
        ]);

        $mediaType200 = new MediaType();
        $mediaType200->setSchema($schema200);


        $mediaType4XX = new MediaType();
        $mediaType4XX->setSchema($schema4XX);


        $response200 = new Response();
        $response200->setContent([
            ContentType::APPLICATION_JSON => $mediaType200
        ]);

        $response4XX = new Response();
        $response4XX->setContent([
            ContentType::APPLICATION_JSON => $mediaType4XX
        ]);

        $operation = new Operation();
        $operation->setResponses([
            "200" => $response200,
            "4XX" => $response4XX
        ]);

        return $operation;
    }

}