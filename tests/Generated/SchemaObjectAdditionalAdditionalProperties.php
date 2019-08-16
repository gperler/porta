<?php

declare(strict_types = 1);

namespace Synatos\PortaTest\Generated;

class SchemaObjectAdditionalAdditionalProperties implements \JsonSerializable
{

    /**
     * @var bool
     */
    protected $x1;

    /**
     * @param bool $x1
     * 
     * @return void
     */
    public function setX1(bool $x1)
    {
        $this->x1 = $x1;
    }

    /**
     * 
     * @return bool
     */
    public function getX1() : bool
    {
        return $this->x1;
    }

    /**
     * @param array $array
     * 
     * @return void
     */
    public function fromArray(array $array)
    {
        foreach ($array as $propertyName => $propertyValue) {
            if ($propertyValue === null) {
                continue;
            }
            switch ($propertyName) {
                case "x1":
                    $this->x1 = $propertyValue;
                    break;
            }
        }
    }

    /**
     * 
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            "x1" => $this->x1,
        ];
    }
}