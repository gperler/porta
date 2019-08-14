<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Operation;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\HttpResponse;
use Synatos\Porta\Model\Response;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\Schema\SchemaValidator;
use Synatos\Porta\Validator\ValidationMessage;

class ResponseBodyValidator
{

    /**
     * @var ReferenceResolver
     */
    private $referenceResolver;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var string
     */
    private $contentType;

    /**
     * @var array
     */
    private $responseBody;


    /**
     * RequestValidator constructor.
     *
     * @param ReferenceResolver $referenceResolver
     */
    public function __construct(ReferenceResolver $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
    }


    /**
     * @param Response $response
     * @param HttpResponse $httpResponse
     *
     * @return array|ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validateResponseBody(Response $response, HttpResponse $httpResponse)
    {
        $this->response = $this->referenceResolver->resolveResponse($response);
        $this->responseBody = $httpResponse->getResponseBody();
        $this->contentType = $httpResponse->getContentType();

        return $this->validateSchema();
    }


    /**
     * @return array
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateSchema(): array
    {
        $schema = $this->getResponseSchema();
        if ($schema === null) {
            return [];
        }

        $schemaValidator = new SchemaValidator($this->referenceResolver);
        return $schemaValidator->validate($schema, $this->responseBody, true, ["ResponseBody"]);
    }


    /**
     * @return Schema|null
     */
    private function getResponseSchema(): ?Schema
    {
        $mediaType = $this->response->getContentByType($this->contentType);
        if ($mediaType === null) {
            return null;
        }
        return $mediaType->getSchema();
    }


}