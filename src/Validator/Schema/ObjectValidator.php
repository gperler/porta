<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class ObjectValidator implements StoppingValidator
{

    const MESSAGE_REQUIRED = "property {PROPERTY} is required ({PATH})";

    const CODE_REQUIRED = "object.required";

    const MESSAGE_MIN_PROPERTY = "property {PROPERTY} minimum number of properties not reached. ({PATH})";

    const CODE_MIN_PROPERTY = "object.minProperties";

    const MESSAGE_MAX_PROPERTY = "property {PROPERTY} is required ({PATH})";

    const CODE_MAX_PROPERTY = "object.minProperties";

    /**
     * @var SchemaValidator
     */
    private $schemaValidator;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string[]
     */
    private $propertyPath;

    /**
     * @var ValidationMessage[]
     */
    private $validationMessageList;


    /**
     * ObjectValidator constructor.
     * @param ReferenceResolver $referenceResolver
     */
    public function __construct(ReferenceResolver $referenceResolver)
    {
        $this->schemaValidator = new SchemaValidator($referenceResolver);
    }


    /**
     * @param Schema $schema
     * @param $value
     * @param array $propertyPath
     * @return array
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        if ($value === null) {
            return [];
        }

        $this->validationMessageList = [];
        $this->schema = $schema;
        $this->value = $value;
        $this->propertyPath = $propertyPath;

        $this->validateObjectRequired();
        $this->validateObjectMinMaxProperties();
        $this->validateObjectProperties();

        return $this->validationMessageList;
    }


    /**
     * @return bool
     */
    public function canContinueOnValidationError(): bool
    {
        return true;
    }


    /**
     *
     */
    private function validateObjectRequired()
    {
        $requiredList = $this->schema->getRequired();

        if ($requiredList === null) {
            return;
        }

        foreach ($requiredList as $required) {
            if (!array_key_exists($required, $this->value)) {
                $propertyPath = $this->propertyPath;
                $propertyPath[] = $required;
                $this->pushValidationMessage(self::MESSAGE_REQUIRED, self::CODE_REQUIRED, $propertyPath);
            }
        }
    }


    /**
     *
     */
    private function validateObjectMinMaxProperties()
    {
        $minProperties = $this->schema->getMinProperties();
        $maxProperties = $this->schema->getMaxProperties();

        $numberOfProperties = count(array_keys($this->value));

        if ($minProperties !== null && $numberOfProperties < $minProperties) {
            $this->pushValidationMessage(self::MESSAGE_MIN_PROPERTY, self::CODE_MIN_PROPERTY, $this->propertyPath);
        }

        if ($maxProperties !== null && $numberOfProperties > $maxProperties) {
            $this->pushValidationMessage(self::MESSAGE_MAX_PROPERTY, self::CODE_MAX_PROPERTY, $this->propertyPath);
        }
    }


    /**
     * @throws InvalidSchemaExceptionException
     * @throws InvalidReferenceException
     */
    private function validateObjectProperties()
    {
        $propertyList = $this->schema->getProperties();
        if ($propertyList === null) {
            return;
        }

        foreach ($propertyList as $name => $propertySchema) {
            $propertyValue = (isset($this->value[$name])) ? $this->value[$name] : null;
            $propertyPath = $this->propertyPath;
            $propertyPath[] = $name;
            $validationMessageList = $this->schemaValidator->validate($propertySchema, $propertyValue, true, $propertyPath);

            $this->pushValidationMessageList($validationMessageList);
        }
    }


    /**
     * @param string $message
     * @param string $code
     * @param array $propertyPath
     */
    private function pushValidationMessage(string $message, string $code, array $propertyPath)
    {
        $this->validationMessageList[] = new ValidationMessage($message, $code, $propertyPath);
    }


    /**
     * @param array $validationMessageList
     */
    private function pushValidationMessageList(array $validationMessageList)
    {
        $this->validationMessageList = array_merge($this->validationMessageList, $validationMessageList);
    }
}