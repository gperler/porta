<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator;

use JsonSerializable;

class ValidationMessage implements JsonSerializable
{

    const MACRO_PROPERTY = "{PROPERTY}";

    const MACRO_PATH = "{PATH}";


    /**
     * @param string $message
     * @param array $propertyPath
     * @return mixed|string
     */
    public static function getMessage(string $message, array $propertyPath)
    {
        if (sizeof($propertyPath) === 0) {
            return $message;
        }
        $path = implode(".", $propertyPath);
        $message = str_replace(self::MACRO_PATH, $path, $message);

        $lastItem = $propertyPath[sizeof($propertyPath) - 1];
        return str_replace(self::MACRO_PROPERTY, $lastItem, $message);
    }


    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string[]
     */
    private $propertyPath;


    /**
     * ValidationMessage constructor.
     * @param string $message
     * @param string $code
     * @param array $propertyPath
     */
    public function __construct(string $message, string $code, array $propertyPath = [])
    {
        $this->message = self::getMessage($message, $propertyPath);
        $this->code = $code;
        $this->propertyPath = $propertyPath;
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            "message" => $this->message,
            "code" => $this->code,
            "propertyPath" => $this->propertyPath
        ];
    }


    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }


    /**
     * @return string[]
     */
    public function getPropertyPath(): array
    {
        return $this->propertyPath;
    }


}