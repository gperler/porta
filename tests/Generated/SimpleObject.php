<?php

declare(strict_types = 1);

namespace Synatos\PortaTest\Generated;

class SimpleObject implements \JsonSerializable
{

    /**
     * @var bool
     */
    protected $prop;

    /**
     * @param bool $prop
     * 
     * @return void
     */
    public function setProp(bool $prop)
    {
        $this->prop = $prop;
    }

    /**
     * 
     * @return bool
     */
    public function getProp() : bool
    {
        return $this->prop;
    }

    /**
     * @param array $array
     * 
     * @return void
     */
    public function fromArray(array $array)
    {
        foreach ($array as $propertyName => $propertyValue) {
            switch ($propertyName) {
                case "prop":
                    $this->prop = $propertyValue;
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
            "prop" => $this->prop,
        ];
    }
}