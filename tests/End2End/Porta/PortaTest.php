<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Porta;

use Codeception\Test\Unit;
use Codeception\Util\Debug;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\ContentType;
use Synatos\Porta\Http\HttpHeader;
use Synatos\Porta\Porta;

class PortaTest extends Unit
{


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function testPorta()
    {
        $porta = new Porta();
        $porta->setBasePath(__DIR__);

        $porta->fromJSONFile(__DIR__ . '/Schema/open-api.schema.json');


        $requestBody = [
            "email" => "email",
            "password" => null
        ];

        $validationMessageList = $porta->validateRequest("/api/security/login-with-password", "post", [
            HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON
        ], [], json_encode($requestBody, JSON_UNESCAPED_SLASHES));

        Debug::debug($validationMessageList);
    }
}