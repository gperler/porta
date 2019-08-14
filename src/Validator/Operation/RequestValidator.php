<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Operation;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\HttpRequest;
use Synatos\Porta\Model\Operation;
use Synatos\Porta\Model\RequestBody;
use Synatos\Porta\Validator\ValidationMessage;

class RequestValidator
{
    /**
     * @var ReferenceResolver
     */
    private $referenceResolver;

    /**
     * @var Operation
     */
    private $operation;

    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var RequestParameterValidator
     */
    private $parameterValidator;

    /**
     * @var RequestBodyValidator
     */
    private $requestBodyValidator;

    /**
     * @var ValidationMessage[]
     */
    private $validationMessageList;


    /**
     * RequestValidator constructor.
     *
     * @param ReferenceResolver $referenceResolver
     */
    public function __construct(ReferenceResolver $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
        $this->parameterValidator = new RequestParameterValidator($referenceResolver);
        $this->requestBodyValidator = new RequestBodyValidator($referenceResolver);
    }


    /**
     * @param Operation $operation
     * @param HttpRequest $httpRequest
     *
     * @return ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Operation $operation, HttpRequest $httpRequest): array
    {
        $this->operation = $operation;
        $this->httpRequest = $httpRequest;
        $this->validationMessageList = [];

        $this->validateParameterList();
        $this->validateRequestBody();

        return $this->validationMessageList;
    }


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateParameterList()
    {
        $parameterList = $this->operation->getParameters();
        if ($parameterList === null) {
            return;
        }
        foreach ($parameterList as $parameter) {
            $validationMessageList = $this->parameterValidator->validateParameter($parameter, $this->httpRequest);
            $this->pushValidationMessageList($validationMessageList);
        }
    }


    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateRequestBody()
    {
        $requestBody = $this->getRequestBody();

        $validationMessageList = $this->requestBodyValidator->validateRequestBody($requestBody, $this->httpRequest);

        $this->pushValidationMessageList($validationMessageList);
    }


    /**
     * @return RequestBody|null
     * @throws InvalidReferenceException
     */
    private function getRequestBody(): ?RequestBody
    {
        $requestBody = $this->operation->getRequestBody();
        if ($requestBody === null) {
            return null;
        }

        return $this->referenceResolver->resolveRequestBody($requestBody);
    }


    /**
     * @param array $validationMessageList
     */
    private function pushValidationMessageList(array $validationMessageList)
    {
        $this->validationMessageList = array_merge($this->validationMessageList, $validationMessageList);
    }

}