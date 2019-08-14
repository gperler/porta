<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class IntegerValidator implements StoppingValidator
{
    const MESSAGE_MINIMUM = "Property {PROPERTY}: value %s has not minimum (%s). ({PATH})";

    const CODE_MINIMUM = "integer.minimum";

    const MESSAGE_MAXIMUM = "Property {PROPERTY}: value %s has not maximum (%s). ({PATH})";

    const CODE_MAXIMUM = "integer.maximum";

    const MESSAGE_MULTIPLE = "Property {PROPERTY}: value %s is not multiple of %s. ({PATH})";

    const CODE_MULTIPLE = "string.pattern";

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
     * @param Schema $schema
     * @param $value
     * @param array $propertyPath
     *
     * @return array
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        if ($value === null) {
            return [];
        }
        $this->setValue($value);
        $this->schema = $schema;

        $this->propertyPath = $propertyPath;
        $this->validationMessageList = [];

        $this->testMinimum();
        $this->testMaximum();
        $this->testMultiple();

        return $this->validationMessageList;
    }


    /**
     *
     */
    private function testMinimum()
    {
        $minimum = $this->schema->getMinimum();

        $exclusiveMinimum = $this->schema->getExclusiveMinimum();

        if (($minimum !== null) && $exclusiveMinimum && $this->value < $minimum) {
            $this->pushValidationMessage(self::MESSAGE_MINIMUM, self::CODE_MINIMUM, $minimum);
        }

        if (($minimum !== null) && !$exclusiveMinimum && $this->value <= $minimum) {
            $this->pushValidationMessage(self::MESSAGE_MINIMUM, self::CODE_MINIMUM, $minimum);
        }
    }


    /**
     *
     */
    private function testMaximum()
    {
        $maximum = $this->schema->getMaximum();

        $exclusiveMaximum = $this->schema->getExclusiveMaximum();

        if (($maximum !== null) && $exclusiveMaximum && $this->value > $maximum) {
            $this->pushValidationMessage(self::MESSAGE_MAXIMUM, self::CODE_MAXIMUM, $maximum);
        }

        if (($maximum !== null) && !$exclusiveMaximum && $this->value >= $maximum) {
            $this->pushValidationMessage(self::MESSAGE_MAXIMUM, self::CODE_MAXIMUM, $maximum);
        }
    }


    /**
     *
     */
    private function testMultiple()
    {
        $multipleOf = $this->schema->getMultipleOf();
        if ($multipleOf === null) {
            return;
        }

        if (fmod($this->value, $multipleOf) !== 0.0) {
            $this->pushValidationMessage(self::MESSAGE_MULTIPLE, self::CODE_MULTIPLE, $multipleOf);
        }
    }


    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = (float)$value;
    }


    /**
     * @return bool
     */
    public function canContinueOnValidationError(): bool
    {
        return true;
    }


    /**
     * @param string $message
     * @param string $code
     * @param $variable
     */
    private function pushValidationMessage(string $message, string $code, $variable)
    {
        $message = sprintf($message, $this->value, $variable);

        $this->validationMessageList[] = new ValidationMessage($message, $code, $this->propertyPath);
    }


}