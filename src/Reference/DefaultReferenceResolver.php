<?php

declare(strict_types=1);

namespace Synatos\Porta\Reference;

use Synatos\Porta\Contract\ReferenceLoader;
use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Model\ArraySerializableModel;
use Synatos\Porta\Model\Example;
use Synatos\Porta\Model\Header;
use Synatos\Porta\Model\Link;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Parameter;
use Synatos\Porta\Model\PathItem;
use Synatos\Porta\Model\Reference;
use Synatos\Porta\Model\RequestBody;
use Synatos\Porta\Model\Response;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Model\SecurityScheme;

class DefaultReferenceResolver implements ReferenceResolver
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
     * @param ReferenceLoader $referenceLoader
     */
    public function setReferenceLoader(ReferenceLoader $referenceLoader)
    {
        $this->referenceLoader = $referenceLoader;
    }


    /**
     * @param OpenAPI $openAPI
     */
    public function setOpenAPI(OpenAPI $openAPI): void
    {
        $this->openAPI = $openAPI;
    }


    /**
     * @param Example $example
     *
     * @return Example
     * @throws InvalidReferenceException
     */
    public function resolveExample(Example $example): Example
    {
        if (!$example->isReference()) {
            return $example;
        }
        return $this->resolve($example->getReference(), new Example());
    }


    /**
     * @param Header $header
     *
     * @return Header
     * @throws InvalidReferenceException
     */
    public function resolveHeader(Header $header): Header
    {
        if (!$header->isReference()) {
            return $header;
        }
        return $this->resolve($header->getReference(), new Header());
    }


    /**
     * @param Link $link
     *
     * @return Link
     * @throws InvalidReferenceException
     */
    public function resolveLink(Link $link): Link
    {
        if (!$link->isReference()) {
            return $link;
        }
        return $this->resolve($link->getReference(), new Link());
    }


    /**
     * @param Parameter $parameter
     *
     * @return Parameter
     * @throws InvalidReferenceException
     */
    public function resolveParameter(Parameter $parameter): Parameter
    {
        if (!$parameter->isReference()) {
            return $parameter;
        }
        return $this->resolve($parameter->getReference(), new Parameter());
    }


    /**
     * @param PathItem $pathItem
     *
     * @return PathItem
     * @throws InvalidReferenceException
     */
    public function resolvePathItem(PathItem $pathItem): PathItem
    {
        if (!$pathItem->isReference()) {
            return $pathItem;
        }
        return $this->resolve($pathItem->getReference(), new PathItem());
    }


    /**
     * @param RequestBody $requestBody
     *
     * @return RequestBody
     * @throws InvalidReferenceException
     */
    public function resolveRequestBody(RequestBody $requestBody): RequestBody
    {
        if (!$requestBody->isReference()) {
            return $requestBody;
        }
        return $this->resolve($requestBody->getReference(), new RequestBody());
    }


    /**
     * @param Response $response
     *
     * @return Response
     * @throws InvalidReferenceException
     */
    public function resolveResponse(Response $response): Response
    {
        if (!$response->isReference()) {
            return $response;
        }
        return $this->resolve($response->getReference(), new Response());
    }


    /**
     * @param Schema $schema
     *
     * @return Schema
     * @throws InvalidReferenceException
     */
    public function resolveSchema(Schema $schema): Schema
    {
        if (!$schema->isReference()) {
            return $schema;
        }
        return $this->resolve($schema->getReference(), new Schema());
    }


    /**
     * @param SecurityScheme $securityScheme
     *
     * @return SecurityScheme
     * @throws InvalidReferenceException
     */
    public function resolveSecurityScheme(SecurityScheme $securityScheme): SecurityScheme
    {
        if (!$securityScheme->isReference()) {
            return $securityScheme;
        }
        return $this->resolve($securityScheme->getReference(), new SecurityScheme());
    }


    /**
     * @param Reference $reference
     * @param ArraySerializableModel $model
     *
     * @return mixed
     * @throws InvalidReferenceException
     */
    private function resolve(Reference $reference, ArraySerializableModel $model)
    {
        $data = $reference->isLocal() ? $this->openAPI->resolveLocalReference($reference) : $this->referenceLoader->loadReference($reference);
        $model->fromArray($data);
        return $model;
    }

}