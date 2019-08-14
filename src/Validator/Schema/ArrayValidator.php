<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class ArrayValidator implements StoppingValidator
{

    const MESSAGE_MIN_ITEMS = "Property {PROPERTY}: minimum items not reached. ({PATH})";

    const CODE_MIN_ITEMS = "array.minItems";

    const MESSAGE_MAX_ITEMS = "Property {PROPERTY}: maximum items exceeded. ({PATH})";

    const CODE_MAX_ITEMS = "array.maxItems";

    const MESSAGE_UNIQUE_ITEMS = "Items are not unique";

    const CODE_UNIQUE_ITEMS = "array.uniqueItems";

    const EXCEPTION_MISSING_ITEMS = "Property {PROPERTY} : Items must be defined for type array ({PATH})";

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
     * ArrayValidator constructor.
     *
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

        $this->schema = $schema;
        $this->value = $value;
        $this->propertyPath = $propertyPath;
        $this->validationMessageList = [];

        if ($schema->getItems() === null) {
            return [];
        }


        $this->validateMinMaxItems();
        $this->validateUniqueItems();
        $this->validateItems();

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
    private function validateMinMaxItems()
    {
        $minItems = $this->schema->getMinItems();
        $maxItems = $this->schema->getMaxItems();

        $numberOfItems = count($this->value);

        if ($minItems !== null && $numberOfItems < $minItems) {
            $this->pushValidationMessage(self::MESSAGE_MIN_ITEMS, self::CODE_MIN_ITEMS);
        }

        if ($maxItems !== null && $numberOfItems > $maxItems) {
            $this->pushValidationMessage(self::MESSAGE_MAX_ITEMS, self::CODE_MAX_ITEMS);
        }
    }


    /**
     *
     */
    private function validateUniqueItems()
    {
        if (!$this->schema->getUniqueItems()) {
            return;
        }
        if (count($this->value) !== count(array_unique($this->value))) {
            $this->pushValidationMessage(self::MESSAGE_UNIQUE_ITEMS, self::CODE_UNIQUE_ITEMS);
        }
    }


    /**
     * @throws InvalidSchemaExceptionException
     * @throws InvalidReferenceException
     */
    private function validateItems()
    {
        $items = $this->schema->getItems();

        foreach ($this->value as $index => $valueItem) {
            $propertyPath = $this->propertyPath;
            $propertyPath[] = $index;
            $validationMessageList = $this->schemaValidator->validate($items, $valueItem, true, $propertyPath);
            $this->pushValidationMessageList($validationMessageList);
        }
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

}