<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator\Operation;

use Codeception\Test\Unit;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\HttpResponse;
use Synatos\Porta\Model\Header;
use Synatos\Porta\Model\Response;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Operation\ResponseHeaderValidator;
use Synatos\Porta\Validator\Schema\TypeValidator;

class ResponseHeaderValidatorTest extends Unit
{


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function test()
    {
        $responseValidator = new ResponseHeaderValidator(new DefaultReferenceResolver());


        $stringSchema = new Schema();
        $stringSchema->setType(Schema::TYPE_STRING);
        $stringSchema->setNullable(true);

        $booleanSchema = new Schema();
        $booleanSchema->setType(Schema::TYPE_BOOLEAN);

        $headerA = new Header();
        $headerA->setRequired(true);
        $headerA->setSchema($stringSchema);

        $headerB = new Header();
        $headerB->setSchema($booleanSchema);

        $response = new Response();
        $response->setHeaders([
            "headerA" => $headerA,
            "headerB" => $headerB
        ]);


        // test happy
        $httpResponse = new HttpResponse(200, [
            "heAdEra" => "str",
            "HEADERb" => "false"
        ], null);

        $validationMessageList = $responseValidator->validateResponseHeader($response, $httpResponse);
        $this->assertCount(0, $validationMessageList);

        // test happy > header is set but value null
        $httpResponse = new HttpResponse(200, [
            "heAdEra" => null,
            "HEADERb" => "false"
        ], null);

        $validationMessageList = $responseValidator->validateResponseHeader($response, $httpResponse);
        $this->assertCount(0, $validationMessageList);

        // test fail
        $httpResponse = new HttpResponse(200, [
            "HEADERb" => "123"
        ], null);
        $validationMessageList = $responseValidator->validateResponseHeader($response, $httpResponse);
        $this->assertCount(2, $validationMessageList);

        $this->assertSame(ResponseHeaderValidator::CODE_REQUIRED_HEADER, $validationMessageList[0]->getCode());
        $this->assertSame(TypeValidator::CODE, $validationMessageList[1]->getCode());
    }


}