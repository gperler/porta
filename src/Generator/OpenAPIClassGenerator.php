<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use Nitria\ClassGenerator;
use RuntimeException;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Validator\Schema\TypeValidator;


class OpenAPIClassGenerator
{


    /**
     * @param OpenAPI $openAPI
     * @param string $className
     * @param string $psrPrefix
     * @param string $baseDir
     */
    public function generate(OpenAPI $openAPI, string $className, string $psrPrefix, string $baseDir)
    {
        $classGenerator = new ClassGenerator($className);

        $classGenerator->addPrivateStaticProperty("openAPI", OpenAPI::class);

        $method = $classGenerator->addPublicStaticMethod("getOpenAPI");

        $method->addIfStart('self::$openAPI === null');
        $method->addCodeLine('self::$openAPI = new OpenAPI();');

        $array = $this->compileArray($openAPI->jsonSerialize());
        $method->addCodeLine('self::$openAPI->fromArray(' . $array . ');');
        $method->addIfEnd();
        $method->addCodeLine('return self::$openAPI;');
        $method->setReturnType(OpenAPI::class, false);

        $classGenerator->writeToPSR4($baseDir, $psrPrefix);
    }


    /**
     * @param array $array
     *
     * @return string
     */
    private function compileArray(array $array): string
    {
        if (TypeValidator::isArray($array)) {
            $arrayParts = [];

            foreach ($array as $item) {
                $arrayParts[] = $this->itemToString($item);
            }

            return '[' . implode(',', $arrayParts) . ']';
        }

        if (TypeValidator::isObject($array)) {
            $arrayParts = [];

            foreach ($array as $key => $item) {
                $arrayParts[] = "'" . $key . "'" . '=>' . $this->itemToString($item);
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
    private function itemToString($item): string
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
            return $this->compileArray($item);
        }
        throw new RuntimeException();
    }

}