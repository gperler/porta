<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator;

use Synatos\Porta\Contract\Validator;
use Synatos\Porta\Validator\Format\FormatEmailValidator;

class FormatValidatorFactory
{
    /**
     * @var Validator[]
     */
    private static $formatValidatorList;

    /**
     * @param string $formatName
     * @param Validator $validator
     */
    public static function addFormatValidator(string $formatName, Validator $validator)
    {
        self::initFormatValidator();
        self::$formatValidatorList[$formatName] = $validator;
    }

    /**
     * @param string $format
     * @return mixed|null
     */
    public static function getFormatValidatorByFormat(string $format): ?Validator
    {
        self::initFormatValidator();
        return (isset(self::$formatValidatorList[$format])) ? self::$formatValidatorList[$format] : null;
    }

    /**
     *
     */
    private static function initFormatValidator()
    {
        if (self::$formatValidatorList !== null) {
            return;
        }
        self::$formatValidatorList = [
            FormatEmailValidator::FORMAT_NAME => new FormatEmailValidator()
        ];
    }
}