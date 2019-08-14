<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class AdditionalPropertiesValidator implements StoppingValidator
{
    /**
     * @var SchemaValidator
     */
    private $schemaValidator;

    /**
     * @var Schema
     */
    private $additionalProperties;

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
        $additionalProperties = $schema->getAdditionalProperties();
        if ($value === null || !is_array($value) || !($additionalProperties instanceof Schema)) {
            return [];
        }

        $this->additionalProperties = $additionalProperties;
        $this->value = $value;
        $this->propertyPath = $propertyPath;
        $this->validationMessageList = [];

        $this->validateAdditionalProperties();

        return $this->validationMessageList;
    }

    /**
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateAdditionalProperties()
    {
        foreach ($this->value as $key => $additionalPropertyValue) {
            $validationMessageList = $this->schemaValidator->validate($this->additionalProperties, $additionalPropertyValue, true, $this->propertyPath);
            $this->pushValidationMessageList($validationMessageList);
        }
    }


    public function canContinueOnValidationError(): bool
    {
        return true;
    }

    /**
     * @param array $validationMessageList
     */
    private function pushValidationMessageList(array $validationMessageList)
    {
        $this->validationMessageList = array_merge($this->validationMessageList, $validationMessageList);
    }

}