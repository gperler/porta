<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Operation;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\HttpResponse;
use Synatos\Porta\Model\Operation;
use Synatos\Porta\Model\Response;
use Synatos\Porta\Validator\ValidationMessage;

class ResponseValidator
{

    /**
     * @var ReferenceResolver
     */
    private $referenceResolver;


    /**
     * @var HttpResponse
     */
    private $httpResponse;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var ValidationMessage[]
     */
    private $validationMessageList;


    /**
     * @var ResponseHeaderValidator
     */
    private $responseHeaderValidator;


    /**
     * @var ResponseBodyValidator
     */
    private $responseBodyValidator;


    /**
     * RequestValidator constructor.
     *
     * @param ReferenceResolver $referenceResolver
     */
    public function __construct(ReferenceResolver $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
        $this->responseHeaderValidator = new ResponseHeaderValidator($referenceResolver);
        $this->responseBodyValidator = new ResponseBodyValidator($referenceResolver);
    }


    /**
     * @param Operation $operation
     * @param HttpResponse $httpResponse
     *
     * @return ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validateResponse(Operation $operation, HttpResponse $httpResponse)
    {
        $this->validationMessageList = [];
        $this->httpResponse = $httpResponse;
        $this->response = $this->getResponse($operation);

        if ($this->response === null) {
            return [];
        }

        $this->validateResponseHeader();
        $this->validateResponseBody();

        return $this->validationMessageList;
    }


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateResponseHeader()
    {
        $validationMessageList = $this->responseHeaderValidator->validateResponseHeader($this->response, $this->httpResponse);
        $this->pushValidationMessageList($validationMessageList);
    }


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateResponseBody()
    {
        $validationMessageList = $this->responseBodyValidator->validateResponseBody($this->response, $this->httpResponse);
        $this->pushValidationMessageList($validationMessageList);
    }


    /**
     * @param Operation $operation
     *
     * @return Response|null
     */
    private function getResponse(Operation $operation): ?Response
    {
        $responses = $operation->getResponses();

        $statusCode = (string)$this->httpResponse->getStatusCode();
        if (isset($responses[$statusCode])) {
            return $responses[$statusCode];
        }

        $statusCode = substr($statusCode, 0, 2) . 'X';
        if (isset($responses[$statusCode])) {
            return $responses[$statusCode];
        }

        $statusCode = substr($statusCode, 0, 1) . 'XX';
        if (isset($responses[$statusCode])) {
            return $responses[$statusCode];
        }

        return (isset($responses["default"])) ? $responses["default"] : null;
    }


    /**
     * @param array $validationMessageList
     */
    private function pushValidationMessageList(array $validationMessageList)
    {
        $this->validationMessageList = array_merge($this->validationMessageList, $validationMessageList);
    }

}