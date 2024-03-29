<?php

declare(strict_types = 1);

namespace Synatos\PortaTest\Generated;

class SchemaArrayObjectAdditionalAdditionalPropertiesItem implements \JsonSerializable
{

    /**
     * @var int|null
     */
    protected ?int $x = null;

    /**
     * @param int $x
     * 
     * @return void
     */
    public function setX(int $x): void
    {
        $this->x = $x;
    }


    /**
     * 
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }


    /**
     * @param array $array
     * 
     * @return void
     */
    public function fromArray(array $array): void
    {
        foreach ($array as $propertyName => $propertyValue) {
            switch ($propertyName) {
                case "x":
                    $this->x = $propertyValue;
                    break;
            }
        }
    }


    /**
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "x" => $this->x,
        ];
    }

}