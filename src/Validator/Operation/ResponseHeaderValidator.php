<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Operation;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\HttpHeader;
use Synatos\Porta\Http\HttpResponse;
use Synatos\Porta\Model\Header;
use Synatos\Porta\Model\Response;
use Synatos\Porta\Validator\Schema\SchemaValidator;
use Synatos\Porta\Validator\ValidationMessage;

class ResponseHeaderValidator
{
    const MESSAGE_REQUIRED_HEADER = "Header '%s' is required.";

    const CODE_REQUIRED_HEADER = "ResponseHeaderValidator.required";

    /**
     * @var ReferenceResolver
     */
    private $referenceResolver;

    /**
     * @var Header[]
     */
    private $headers;

    /**
     * @var HttpHeader
     */
    private $httpHeader;


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
    }


    /**
     * @param Response $response
     * @param HttpResponse $httpResponse
     *
     * @return array|ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validateResponseHeader(Response $response, HttpResponse $httpResponse)
    {
        $this->httpHeader = $httpResponse->getHeader();
        $this->headers = $response->getHeaders();
        $this->validationMessageList = [];

        $this->validateHeaderList();

        return $this->validationMessageList;
    }


    /**
     *
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateHeaderList()
    {
        if ($this->headers === null) {
            return;
        }
        foreach ($this->headers as $name => $header) {
            $validationMessageList = $this->validateHeader($name, $header);
            $this->pushValidationMessageList($validationMessageList);
        }
    }


    /**
     * @param string $name
     * @param Header $header
     *
     * @return array|ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateHeader(string $name, Header $header)
    {
        $this->referenceResolver->resolveHeader($header);

        if (!$this->httpHeader->isSet($name)) {
            return $this->createRequiredMessage($name);
        }

        $headerValue = $this->httpHeader->getValue($name);

        $schema = $header->getSchema();
        $schemaValidator = new SchemaValidator($this->referenceResolver);
        return $schemaValidator->validate($schema, $headerValue, false, ["ResponseHeader"]);
    }


    /**
     * @param string $name
     *
     * @return array
     */
    private function createRequiredMessage(string $name): array
    {
        $message = sprintf(self::MESSAGE_REQUIRED_HEADER, $name);
        return [
            new ValidationMessage($message, self::CODE_REQUIRED_HEADER)
        ];
    }


    /**
     * @param array $validationMessageList
     */
    private function pushValidationMessageList(array $validationMessageList)
    {
        $this->validationMessageList = array_merge($this->validationMessageList, $validationMessageList);
    }

}