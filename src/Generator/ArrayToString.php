<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use RuntimeException;
use Synatos\Porta\Validator\Schema\TypeValidator;

class ArrayToString
{

    /**
     * @param array $array
     *
     * @return string
     */
    public static function compileArray(array $array): string
    {
        if (TypeValidator::isArray($array)) {
            $arrayParts = [];

            foreach ($array as $item) {
                $arrayParts[] = self::itemToString($item);
            }

            return '[' . implode(',', $arrayParts) . ']';
        }

        if (TypeValidator::isObject($array)) {
            $arrayParts = [];

            foreach ($array as $key => $item) {
                $arrayParts[] = "'" . $key . "'" . '=>' . self::itemToString($item);
            }

            return '[' . implode(',', $arrayParts) . ']';
        }
        throw new RuntimeException();
    }


    /**
     * @param $item
     *
     * @return string
     */
    public static function itemToString($item): string
    {
        if ($item === null) {
            return "null";
        }

        if (is_bool($item)) {
            return ($item) ? 'true' : 'false';
        }

        if (is_int($item) || is_float($item)) {
            return (string)$item;
        }

        if (is_string($item)) {
            return "'" . $item . "'";
        }

        if (is_array($item)) {
            return self::compileArray($item);
        }
        throw new RuntimeException();
    }

}