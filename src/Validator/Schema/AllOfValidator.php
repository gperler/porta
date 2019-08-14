<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class AllOfValidator implements StoppingValidator
{
    const MESSAGE_NOT_ALL_OF_ARE_VALID = "Property {PROPERTY}: not all schemas are valid. ({PATH})";

    const CODE_NOT_ALL_OF_ARE_VALID = "allOf.notAllOfAreValid";

    /**
     * @var ReferenceResolver
     */
    private $referenceResolver;

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
     * @param ReferenceResolver $referenceResolver
     */
    public function __construct(ReferenceResolver $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
    }

    /**
     * @param Schema $schema
     * @param $value
     * @param array $propertyPath
     * @return ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        $schemaList = $schema->getAllOf();
        if ($value === null || empty($schemaList)) {
            return [];
        }
        $this->schema = $schema;
        $this->value = $value;
        $this->propertyPath = $propertyPath;
        $this->validationMessageList = [];

        $this->validateSchemaList($schemaList);

        if (count($this->validationMessageList) !== 0) {
            $this->unshiftValidationMessage(self::MESSAGE_NOT_ALL_OF_ARE_VALID, self::CODE_NOT_ALL_OF_ARE_VALID);
        }

        return $this->validationMessageList;
    }


    /**
     * @param array $schemaList
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateSchemaList(array $schemaList)
    {
        foreach ($schemaList as $schema) {
            $this->validateSchema($schema);
        }
    }


    /**
     * @param Schema $schema
     * @throws InvalidSchemaExceptionException
     * @throws InvalidReferenceException
     */
    private function validateSchema(Schema $schema)
    {
        $schemaValidator = new SchemaValidator($this->referenceResolver);
        $schemaValidator->validate($schema, $this->value, true, $this->propertyPath);
        $validationMessageList = $schemaValidator->getValidationMessageList();
        $this->pushValidationMessageList($validationMessageList);
    }


    /**
     * @param string $message
     * @param string $code
     */
    private function unshiftValidationMessage(string $message, string $code)
    {
        array_unshift($this->validationMessageList, new ValidationMessage($message, $code, $this->propertyPath));
    }


    /**
     * @param array $validationMessageList
     */
    private function pushValidationMessageList(array $validationMessageList)
    {
        $this->validationMessageList = array_merge($this->validationMessageList, $validationMessageList);
    }


    public function canContinueOnValidationError(): bool
    {
        return true;
    }

}