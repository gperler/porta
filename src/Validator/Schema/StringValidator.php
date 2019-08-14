<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class StringValidator implements StoppingValidator
{
    const MESSAGE_MIN_LENGTH = "Property {PROPERTY}: value '%s' has not minimum length (%s). ({PATH})";

    const CODE_MIN_LENGTH = "array.minItems";

    const MESSAGE_MAX_LENGTH = "Property {PROPERTY}: value '%s' is longer than maximum length (%s). ({PATH})";

    const CODE_MAX_LENGTH = "array.maxItems";

    const MESSAGE_PATTERN = "Property {PROPERTY}: value '%s' does not match pattern (%s). ({PATH})";

    const CODE_PATTERN = "string.pattern";

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
     * @return ValidationMessage[]
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

        $this->validatePattern();
        $this->validateLength();

        return $this->validationMessageList;
    }


    /**
     *
     */
    private function validatePattern()
    {
        $pattern = $this->schema->getPattern();
        if ($pattern === null) {
            return;
        }
        $pattern = trim($pattern, "/");

        if (preg_match("/" . $pattern . "/", $this->value) === 1) {
            return;
        }
        $this->pushValidationMessage(self::MESSAGE_PATTERN, self::CODE_PATTERN, $pattern);
    }


    /**
     *
     */
    private function validateLength()
    {
        $minLength = $this->schema->getMinLength();
        $maxLength = $this->schema->getMaxLength();

        $valueLength = strlen($this->value);


        if ($minLength !== null && $valueLength < $minLength) {
            $this->pushValidationMessage(self::MESSAGE_MIN_LENGTH, self::CODE_MIN_LENGTH, $minLength);
        }

        if ($maxLength !== null && $valueLength > $maxLength) {
            $this->pushValidationMessage(self::MESSAGE_MAX_LENGTH, self::CODE_MAX_LENGTH, $maxLength);
        }
    }


    /**
     * @return bool
     */
    public function canContinueOnValidationError(): bool
    {
        return false;
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