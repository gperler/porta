<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Discriminator;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

/**
 * My understanding of the specification (might be wrong) either use discriminator to determine schema
 * or only one is supposed to match
 *
 * (An instance validates successfully against this keyword if it validates successfully against exactly one schema defined by this keyword's value.)
 *
 * Class OneOfValidator
 *
 * @package Synatos\Porta\Validator\Schema
 */
class OneOfValidator implements StoppingValidator
{
    const EXCEPTION_NO_DISCRIMINATOR_FOUND = 'Discriminator is missing property name';

    const MESSAGE_NOT_ALL_OF_ARE_VALID = "Property {PROPERTY}: not valid against schema. ({PATH})";

    const CODE_NOT_ALL_OF_ARE_VALID = "oneOf.notOneIsValid";

    const MESSAGE_DISCRIMINATOR_VALUE_MISSING = "Expected to find discriminator value in '%s' {PROPERTY}. ({PATH})";

    const CODE_DISCRIMINATOR_VALUE_MISSING = "oneOf.discriminatorValueMissing";

    const MESSAGE_NO_SCHEMA_FOR_DISCRIMINATOR_VALUE = "No Schema found for discriminator value '%s' {PROPERTY}. ({PATH})";

    const CODE_NO_SCHEMA_FOR_DISCRIMINATOR_VALUE = "oneOf.noSchemaForDiscriminatorValue";

    const MESSAGE_MORE_THAN_ONE_SCHEMA_MATCHED = "More than one schema matched. (consider using anyOf) {PROPERTY}. ({PATH})";

    const CODE_MORE_THAN_ONE_SCHEMA_MATCHED = "oneOf.moreThanOneSchemaMatched";


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
     *
     * @return ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        $schemaList = $schema->getOneOf();
        if ($value === null || empty($schemaList)) {
            return [];
        }
        $this->schema = $schema;
        $this->value = $value;
        $this->propertyPath = $propertyPath;
        $this->validationMessageList = [];

        $schemaList = $this->getSchemaList();
        if (sizeof($this->validationMessageList) !== 0) {
            return $this->validationMessageList;
        }

        $this->validateSchemaList($schemaList);

        return $this->validationMessageList;
    }


    /**
     * @param array $schemaList
     *
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateSchemaList(array $schemaList)
    {
        $validationMessagePerSchema = [];

        foreach ($schemaList as $index => $schema) {
            $validationMessagePerSchema[$index] = $this->validateSchema($schema);
        }

        $this->checkHowManyValidationsPassed($validationMessagePerSchema);
    }


    /**
     * @param array $validationMessagePerSchema
     */
    private function checkHowManyValidationsPassed(array $validationMessagePerSchema)
    {
        $positiveValidationCount = 0;

        foreach ($validationMessagePerSchema as $schemaValidationMessage) {
            if (count($schemaValidationMessage) === 0) {
                $positiveValidationCount++;
            }
        }
        if ($positiveValidationCount === 1) {
            return;
        }

        if ($positiveValidationCount > 1) {
            $this->pushValidationMessage(self::MESSAGE_MORE_THAN_ONE_SCHEMA_MATCHED, self::CODE_MORE_THAN_ONE_SCHEMA_MATCHED);
            return;
        }
        foreach ($validationMessagePerSchema as $validationMessageList) {
            $this->pushValidationMessageList($validationMessageList);
        }
    }


    /**
     * @param Schema $schema
     *
     * @return ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function validateSchema(Schema $schema): array
    {
        $schemaValidator = new SchemaValidator($this->referenceResolver);
        $schemaValidator->validate($schema, $this->value, true, $this->propertyPath);
        return $schemaValidator->getValidationMessageList();
    }


    /**
     * @return array
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function getSchemaList(): array
    {
        $discriminator = $this->schema->getDiscriminator();
        if ($discriminator === null) {
            return $this->schema->getOneOf();
        }

        $discriminatorSchema = $this->getDiscriminatorSchema($discriminator);

        return $discriminatorSchema !== null ? [$discriminatorSchema] : [];
    }


    /**
     * @param Discriminator $discriminator
     *
     * @return mixed|Schema|null
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function getDiscriminatorSchema(Discriminator $discriminator)
    {
        $discriminatorValue = $this->getDiscriminatorValue($discriminator);
        if ($discriminatorValue === null) {
            return null;
        }
        $schema = $this->getDiscriminatorSchemaByMapping($discriminator, $discriminatorValue);
        if ($schema !== null) {
            return $schema;
        }
        return $this->getDiscriminatorSchemaByValue($discriminatorValue);
    }


    /**
     * @param string $discriminatorValue
     *
     * @return Schema|null
     */
    private function getDiscriminatorSchemaByValue(string $discriminatorValue): ?Schema
    {
        $oneOfList = $this->schema->getOneOf();
        foreach ($oneOfList as $oneOf) {
            $reference = $oneOf->getReference();
            if ($reference === null) {
                continue;
            }
            $referenceName = $reference->getLastPartOfLocalPath();
            if ($referenceName === $discriminatorValue) {
                return $oneOf;
            }
        }

        $message = sprintf(self::MESSAGE_NO_SCHEMA_FOR_DISCRIMINATOR_VALUE, $discriminatorValue);
        $this->pushValidationMessage($message, self::CODE_NO_SCHEMA_FOR_DISCRIMINATOR_VALUE);

        return null;
    }


    /**
     * @param Discriminator $discriminator
     * @param string $discriminatorValue
     *
     * @return Schema|null
     * @throws InvalidReferenceException
     */
    private function getDiscriminatorSchemaByMapping(Discriminator $discriminator, string $discriminatorValue)
    {
        $reference = $discriminator->getMappingReference($discriminatorValue);
        if ($reference === null) {
            return null;
        }
        $schema = new Schema();
        $schema->setRef($reference);
        return $this->referenceResolver->resolveSchema($schema);
    }


    /**
     * @param Discriminator $discriminator
     *
     * @return string|null
     * @throws InvalidSchemaExceptionException
     */
    private function getDiscriminatorValue(Discriminator $discriminator)
    {
        $propertyName = $discriminator->getPropertyName();
        if ($propertyName === null) {
            throw new InvalidSchemaExceptionException(self::EXCEPTION_NO_DISCRIMINATOR_FOUND, $this->propertyPath);
        }
        if (!is_array($this->value) || !isset($this->value[$propertyName])) {
            $message = sprintf(self::MESSAGE_DISCRIMINATOR_VALUE_MISSING, $propertyName);
            $this->pushValidationMessage($message, self::CODE_DISCRIMINATOR_VALUE_MISSING);
            return null;
        }

        return $this->value[$propertyName];
    }


    /**
     * @param string $message
     * @param string $code
     */
    private function pushValidationMessage(string $message, string $code)
    {
        $this->validationMessageList[] = new ValidationMessage($message, $code, $this->propertyPath);
    }


    /**
     * @param array $validationMessageList
     */
    private function pushValidationMessageList(array $validationMessageList)
    {
        $this->validationMessageList = array_merge($this->validationMessageList, $validationMessageList);
    }


    /**
     * @return bool
     */
    public function canContinueOnValidationError(): bool
    {
        return true;
    }


}