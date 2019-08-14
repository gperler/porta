<?php


namespace Synatos\Porta\Contract;


use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Model\Example;
use Synatos\Porta\Model\Header;
use Synatos\Porta\Model\Link;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Parameter;
use Synatos\Porta\Model\PathItem;
use Synatos\Porta\Model\RequestBody;
use Synatos\Porta\Model\Response;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Model\SecurityScheme;

interface ReferenceResolver
{

    /**
     * @param OpenAPI $openAPI
     *
     * @return void
     */
    public function setOpenAPI(OpenAPI $openAPI): void;


    /**
     * @param Example $example
     *
     * @return Example
     * @throws InvalidReferenceException
     */
    public function resolveExample(Example $example): Example;


    /**
     * @param Header $header
     *
     * @return Header
     * @throws InvalidReferenceException
     */
    public function resolveHeader(Header $header): Header;


    /**
     * @param Link $link
     *
     * @return Link
     * @throws InvalidReferenceException
     */
    public function resolveLink(Link $link): Link;


    /**
     * @param Parameter $parameter
     *
     * @return Parameter
     * @throws InvalidReferenceException
     */
    public function resolveParameter(Parameter $parameter): Parameter;


    /**
     * @param PathItem $pathItem
     *
     * @return PathItem
     * @throws InvalidReferenceException
     */
    public function resolvePathItem(PathItem $pathItem): PathItem;


    /**
     * @param RequestBody $requestBody
     *
     * @return RequestBody
     * @throws InvalidReferenceException
     */
    public function resolveRequestBody(RequestBody $requestBody): RequestBody;


    /**
     * @param Response $response
     *
     * @return Response
     * @throws InvalidReferenceException
     */
    public function resolveResponse(Response $response): Response;


    /**
     * @param Schema $schema
     *
     * @return Schema
     * @throws InvalidReferenceException
     */
    public function resolveSchema(Schema $schema): Schema;


    /**
     * @param SecurityScheme $securityScheme
     *
     * @return SecurityScheme
     * @throws InvalidReferenceException
     */
    public function resolveSecurityScheme(SecurityScheme $securityScheme): SecurityScheme;

}