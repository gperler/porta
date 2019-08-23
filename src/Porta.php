<?php

declare(strict_types=1);

namespace Synatos\Porta;

use Synatos\Porta\Contract\ReferenceLoader;
use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\HttpRequest;
use Synatos\Porta\Http\HttpResponse;
use Synatos\Porta\Http\RouteMatcher;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Operation;
use Synatos\Porta\Reference\DefaultReferenceLoader;
use Synatos\Porta\Reference\DefaultReferenceResolver;
use Synatos\Porta\Validator\Operation\RequestValidator;
use Synatos\Porta\Validator\Operation\ResponseValidator;
use Synatos\Porta\Validator\ValidationMessage;

class Porta
{
    /**
     * @var OpenAPI
     */
    private $openAPI;

    /**
     * @var ReferenceLoader
     */
    private $referenceLoader;

    /**
     * @var ReferenceResolver
     */
    private $referenceResolver;

    /**
     * @var RequestValidator
     */
    private $requestValidator;


    /**
     * @var ResponseValidator
     */
    private $responseValidator;


    /**
     * Porta constructor.
     *
     * @param ReferenceResolver|null $referenceResolver
     */
    public function __construct(ReferenceResolver $referenceResolver = null)
    {
        $this->openAPI = new OpenAPI();
        $this->referenceLoader = new DefaultReferenceLoader();

        if ($referenceResolver === null) {
            $this->referenceResolver = new DefaultReferenceResolver();
            $this->referenceResolver->setOpenAPI($this->openAPI);
            $this->referenceResolver->setReferenceLoader($this->referenceLoader);
        } else {
            $this->referenceResolver = $referenceResolver;
        }

        $this->requestValidator = new RequestValidator($this->referenceResolver);
        $this->responseValidator = new ResponseValidator($this->referenceResolver);
    }


    /**
     * @param OpenAPI $openAPI
     */
    public function setOpenAPI(OpenAPI $openAPI)
    {
        $this->openAPI = $openAPI;
        $this->referenceResolver->setOpenAPI($this->openAPI);
    }


    /**
     * @param string $basePath
     */
    public function setBasePath(string $basePath)
    {
        $this->referenceLoader->setBasePath($basePath);
    }


    /**
     * @param string $fileName
     */
    public function fromYamlFile(string $fileName)
    {
        $this->openAPI->fromYamlFile($fileName);
    }


    /**
     * @param string $fileName
     */
    public function fromJSONFile(string $fileName)
    {
        $this->openAPI->fromJSONFile($fileName);
    }


    /**
     * @param array $data
     */
    public function fromArray(array $data)
    {
        $this->openAPI->fromArray($data);
    }


    /**
     * @param string $path
     * @param string $httpMethod
     * @param array $header
     * @param array $query
     * @param string|null $requestBody
     *
     * @return array
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validateRequest(string $path, string $httpMethod, array $header = [], array $query = [], string $requestBody = null): array
    {
        $routeMatcher = $this->findRoute($path);
        if ($routeMatcher === null) {
            return [];
        }

        $operation = $this->findOperation($routeMatcher, $httpMethod);
        if ($operation === null) {
            return [];
        }

        $httpRequest = new HttpRequest($path, $httpMethod, $header, $routeMatcher->getRouteParameterList(), $query, $requestBody);

        return $this->validateOperationRequest($operation, $httpRequest);
    }


    /**
     * @param Operation $operation
     * @param HttpRequest $httpRequest
     *
     * @return array|Validator\ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validateOperationRequest(Operation $operation, HttpRequest $httpRequest)
    {
        return $this->requestValidator->validate($operation, $httpRequest);
    }


    /**
     * @param string $path
     * @param string $method
     * @param int $statusCode
     * @param array $responseHeader
     * @param array $responseBody
     *
     * @return array|Validator\ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validateResponse(string $path, string $method, int $statusCode, array $responseHeader, array $responseBody)
    {
        $routeMatcher = $this->findRoute($path);
        if ($routeMatcher === null) {
            return [];
        }

        $operation = $this->findOperation($routeMatcher, $method);
        if ($operation === null) {
            return [];
        }

        $httpResponse = new HttpResponse($statusCode, $responseHeader, $responseBody);

        return $this->responseValidator->validateResponse($operation, $httpResponse);
    }


    /**
     * @param Operation $operation
     * @param HttpResponse $httpResponse
     *
     * @return ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validateOperationResponse(Operation $operation, HttpResponse $httpResponse)
    {
        return $this->responseValidator->validateResponse($operation, $httpResponse);
    }


    /**
     * @param string $path
     *
     * @return RouteMatcher|null
     */
    private function findRoute(string $path): ?RouteMatcher
    {
        $routeList = $this->openAPI->getRouteList();
        return RouteMatcher::findRoute($routeList, $path);
    }


    /**
     * @param RouteMatcher $routeMatcher
     * @param string $method
     *
     * @return Operation|null
     */
    private function findOperation(RouteMatcher $routeMatcher, string $method): ?Operation
    {
        $route = $routeMatcher->getRoute();
        $pathItem = $this->openAPI->getPathItemByPath($route);
        return $pathItem->getOperationByMethod($method);
    }
}