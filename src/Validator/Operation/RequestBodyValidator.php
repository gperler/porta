<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Operation;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\HttpRequest;
use Synatos\Porta\Model\RequestBody;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\Schema\SchemaValidator;
use Synatos\Porta\Validator\ValidationMessage;

class RequestBodyValidator
{

    const MESSAGE_REQUEST_BODY_REQUIRED = "RequestBody  is required.";

    const CODE_REQUEST_BODY_REQUIRED = "requestBodyValidator.required";

    /**
     * @var ReferenceResolver
     */
    private $referenceResolver;

    /**
     * @var RequestBody;
     */
    private $requestBody;

    /**
     * @var HttpRequest
     */
    private $httpRequest;


    /**
     * ParameterValidator constructor.
     *
     * @param ReferenceResolver $referenceResolver
     */
    public function __construct(ReferenceResolver $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
    }


    /**
     * @param RequestBody $requestBody
     * @param HttpRequest $httpRequest
     *
     * @return ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validateRequestBody(?RequestBody $requestBody, HttpRequest $httpRequest)
    {
        if ($requestBody === null) {
            return [];
        }
        $this->requestBody = $this->referenceResolver->resolveRequestBody($requestBody);
        $this->httpRequest = $httpRequest;

        return $this->validateSchema();
    }


    /**
     * @return array
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateSchema(): array
    {
        $requestBody = $this->httpRequest->getRequestBodyJSON();
        if ($this->requestBody->getRequired() && $requestBody === null) {
            return $this->createRequiredMessage();
        }

        $schema = $this->getRequestSchema();
        if ($schema === null) {
            return [];
        }

        $schemaValidator = new SchemaValidator($this->referenceResolver);
        return $schemaValidator->validate($schema, $requestBody, true, ["RequestBody"]);
    }


    /**
     * @return Schema
     */
    private function getRequestSchema(): ?Schema
    {
        $contentType = $this->httpRequest->getContentType();

        $mediaType = $this->requestBody->getContentByType($contentType);

        if ($mediaType === null) {
            return null;
        }
        return $mediaType->getSchema();
    }


    /**
     * @return ValidationMessage[]
     */
    private function createRequiredMessage(): array
    {
        return [
            new ValidationMessage(self::MESSAGE_REQUEST_BODY_REQUIRED, self::CODE_REQUEST_BODY_REQUIRED)
        ];
    }

}