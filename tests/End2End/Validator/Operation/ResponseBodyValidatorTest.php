<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Validator\Operation;

use Codeception\Test\Unit;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\ContentType;
use Synatos\Porta\Http\HttpHeader;
use Synatos\Porta\Http\HttpResponse;
use Synatos\Porta\Model\MediaType;
use Synatos\Porta\Model\Response;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Operation\ResponseBodyValidator;
use Synatos\Porta\Validator\Schema\NullableValidator;
use Synatos\Porta\Validator\Schema\TypeValidator;

class ResponseBodyValidatorTest extends Unit
{


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function testResponseBodyValidator()
    {
        $responseBodyValidator = new ResponseBodyValidator(new DefaultReferenceResolver());

        $stringSchema = new Schema();
        $stringSchema->setType(Schema::TYPE_STRING);
        $stringSchema->setNullable(true);

        $booleanSchema = new Schema();
        $booleanSchema->setType(Schema::TYPE_BOOLEAN);

        $schema = new Schema();
        $schema->setType(Schema::TYPE_OBJECT);
        $schema->setProperties([
            "string" => $stringSchema,
            "bool" => $booleanSchema
        ]);

        $mediaType = new MediaType();
        $mediaType->setSchema($schema);

        $response = new Response();
        $response->setContent([
            ContentType::APPLICATION_JSON => $mediaType
        ]);


        // test happy
        $httpResponse = new HttpResponse(200, [
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON,
        ], [
            "string" => "string",
            "bool" => true
        ]);

        $validationMessageList = $responseBodyValidator->validateResponseBody($response, $httpResponse);
        $this->assertCount(0, $validationMessageList);


        // test fail
        $httpResponse = new HttpResponse(200, [
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON,
        ], null);

        $validationMessageList = $responseBodyValidator->validateResponseBody($response, $httpResponse);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(NullableValidator::CODE, $validationMessageList[0]->getCode());


        // test fail > make sure body is validated strict
        $httpResponse = new HttpResponse(200, [
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON,
        ], [
            "bool" => "false"
        ]);

        $validationMessageList = $responseBodyValidator->validateResponseBody($response, $httpResponse);
        $this->assertCount(1, $validationMessageList);
        $this->assertSame(TypeValidator::CODE, $validationMessageList[0]->getCode());
    }
}