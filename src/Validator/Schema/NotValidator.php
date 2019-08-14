<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class NotValidator implements StoppingValidator
{
    const MESSAGE_NOT_SCHEMA_IS_VALID = "Property {PROPERTY}: matches schema (should not). ({PATH})";

    const CODE_NOT_SCHEMA_IS_VALID = "not.schemaMatches";

    /**
     * @var SchemaValidator
     */
    private $schemaValidator;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string[]
     */
    private $propertyPath;


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
     * @return ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        $notSchema = $schema->getNot();
        if ($value === null || $notSchema === null) {
            return [];
        }
        $this->value = $value;
        $this->propertyPath = $propertyPath;

        $schemaNotValid = $this->isSchemaNotValid($notSchema);
        if ($schemaNotValid) {
            return [];
        }

        return $this->createSchemaValidMessage();
    }


    /**
     * @param Schema $notSchema
     * @return bool
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    private function isSchemaNotValid(Schema $notSchema): bool
    {
        $validationMessageList = $this->schemaValidator->validate($notSchema, $this->value, true, $this->propertyPath);
        return count($validationMessageList) !== 0;
    }


    /**
     * @return ValidationMessage[]
     */
    private function createSchemaValidMessage(): array
    {
        return [
            new ValidationMessage(self::MESSAGE_NOT_SCHEMA_IS_VALID, self::CODE_NOT_SCHEMA_IS_VALID, $this->propertyPath)
        ];
    }

    public function canContinueOnValidationError(): bool
    {
        return true;
    }

}