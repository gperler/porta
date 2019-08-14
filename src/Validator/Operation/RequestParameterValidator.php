<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Operation;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Http\HttpRequest;
use Synatos\Porta\Model\Parameter;
use Synatos\Porta\Validator\Schema\SchemaValidator;
use Synatos\Porta\Validator\ValidationMessage;

class RequestParameterValidator
{

    const MESSAGE_REQUIRED_PARAMETER = "Parameter '%s' is required.";

    const CODE_REQUIRED_PARAMETER = "ParameterValidator.required";


    /**
     * @var ReferenceResolver
     */
    private $referenceResolver;

    /**
     * @var Parameter;
     */
    private $parameter;

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
     * @param Parameter $parameter
     * @param HttpRequest $httpRequest
     *
     * @return array|ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validateParameter(Parameter $parameter, HttpRequest $httpRequest): array
    {
        $this->parameter = $this->referenceResolver->resolveParameter($parameter);
        $this->httpRequest = $httpRequest;

        return $this->validateSchema();
    }


    /**
     * @return array|ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateSchema()
    {
        if ($this->parameter->getRequired() && !$this->isValueSet()) {
            return $this->createRequiredMessage();
        }

        $value = $this->getValue();
        $schema = $this->parameter->getSchema();

        $schemaValidator = new SchemaValidator($this->referenceResolver);
        $propertyPath = [
            "Parameter",
            $this->parameter->getIn(),
            $this->parameter->getName()
        ];
        return $schemaValidator->validate($schema, $value, false, $propertyPath);
    }


    /**
     * @return ValidationMessage[]
     */
    private function createRequiredMessage(): array
    {
        $message = sprintf(self::MESSAGE_REQUIRED_PARAMETER, $this->parameter->getName());
        return [
            new ValidationMessage($message, self::CODE_REQUIRED_PARAMETER)
        ];
    }


    /**
     * @return mixed|null
     */
    private function getValue()
    {
        $name = $this->parameter->getName();
        switch ($this->parameter->getIn()) {
            case Parameter::IN_PATH:
                return $this->httpRequest->getRouteParameter()->getValue($name);
            case Parameter::IN_HEADER:
                return $this->httpRequest->getHeader()->getValue($name);
            case Parameter::IN_QUERY:
                return $this->httpRequest->getQuery()->getValue($name);
            case Parameter::IN_COOKIE:
                return $this->httpRequest->getCookie()->getValue($name);
            default:
                return null;
        }
    }


    /**
     * @return bool
     */
    private function isValueSet(): bool
    {
        $name = $this->parameter->getName();
        switch ($this->parameter->getIn()) {
            case Parameter::IN_PATH:
                return $this->httpRequest->getRouteParameter()->isSet($name);
            case Parameter::IN_HEADER:
                return $this->httpRequest->getHeader()->isSet($name);
            case Parameter::IN_QUERY:
                return $this->httpRequest->getQuery()->isSet($name);
            case Parameter::IN_COOKIE:
                return $this->httpRequest->getCookie()->isSet($name);
            default:
                return false;
        }
    }
}