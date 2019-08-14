<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class TypeValidator implements StoppingValidator
{

    const CODE = "type.invalidType";

    const MESSAGE = "Value in {PROPERTY} is not of specified type. ({PATH})";

    const EXCEPTION_INVALID_TYPE = "type '%s' of field {PROPERTY} is invalid. ({PATH})";

    /**
     * @var bool
     */
    private $strictTypes;


    public function __construct(bool $strictTypes)
    {
        $this->strictTypes = $strictTypes;
    }


    /**
     * @param $value
     *
     * @return bool
     */
    public static function isArray($value): bool
    {
        return is_array($value) && (sizeof($value) === 0 || array_keys($value) === range(0, count($value) - 1));
    }


    /**
     * @param $value
     *
     * @return bool
     */
    public static function isObject($value): bool
    {
        return is_array($value) && (sizeof($value) === 0 || array_keys($value) !== range(0, count($value) - 1));
    }


    /**
     * @param Schema $schema
     * @param $value
     * @param array $propertyPath
     *
     * @return ValidationMessage[]
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        $type = $schema->getType();
        if ($value === null || $type === null) {
            return [];
        }

        if ($this->strictTypes) {
            return $this->validateStrictTypes($type, $value, $propertyPath);
        }
        return $this->validateType($type, $value, $propertyPath);
    }


    /**
     * @return bool
     */
    public function canContinueOnValidationError(): bool
    {
        return false;
    }


    /**
     * @param string $type
     * @param $value
     * @param array $propertyPath
     *
     * @return array
     * @throws InvalidSchemaExceptionException
     */
    private function validateType(string $type, $value, array $propertyPath)
    {
        $isValid = true;
        switch ($type) {
            case Schema::TYPE_BOOLEAN:
                $isValid = is_bool($value) || (is_string($value) && ($value === 'true' || $value === 'false'));
                break;
            case Schema::TYPE_INTEGER:
                $isValid = is_int($value) || (is_string($value) && preg_replace('/[0-9\-]/', '', $value) === "");
                break;
            case Schema::TYPE_NUMBER:
                $isValid = is_float($value) || is_int($value) || (is_string($value) && preg_replace('/[0-9\.\-]/', '', $value) === "");
                break;
            case Schema::TYPE_STRING:
                $isValid = is_string($value);
                break;
            case Schema::TYPE_ARRAY:
                $isValid = self::isArray($value);
                break;
            case Schema::TYPE_OBJECT:
                $isValid = self::isObject($value);
                break;
            default:
                $message = sprintf(self::EXCEPTION_INVALID_TYPE, $type);
                throw new InvalidSchemaExceptionException($message, $propertyPath);
        }
        return $isValid ? [] : $this->createMessage($propertyPath);
    }


    /**
     * @param string $type
     * @param $value
     * @param array $propertyPath
     *
     * @return array
     * @throws InvalidSchemaExceptionException
     */
    private function validateStrictTypes(string $type, $value, array $propertyPath)
    {
        $isValid = true;
        switch ($type) {
            case Schema::TYPE_BOOLEAN:
                $isValid = is_bool($value);
                break;
            case Schema::TYPE_INTEGER:
                $isValid = is_int($value);
                break;
            case Schema::TYPE_NUMBER:
                $isValid = is_float($value) || is_int($value);
                break;
            case Schema::TYPE_STRING:
                $isValid = is_string($value);
                break;
            case Schema::TYPE_ARRAY:
                $isValid = self::isArray($value);
                break;
            case Schema::TYPE_OBJECT:
                $isValid = self::isObject($value);
                break;
            default:
                $message = sprintf(self::EXCEPTION_INVALID_TYPE, $type);
                throw new InvalidSchemaExceptionException($message, $propertyPath);
        }
        return $isValid ? [] : $this->createMessage($propertyPath);
    }


    /**
     * @param array $propertyPath
     *
     * @return array
     */
    private function createMessage(array $propertyPath)
    {
        return [
            new ValidationMessage(self::MESSAGE, self::CODE, $propertyPath)
        ];
    }
}