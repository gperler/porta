<?php

declare(strict_types = 1);

namespace Synatos\PortaTest\Generated;

class SimpleObject implements \JsonSerializable
{

    /**
     * @var bool|null
     */
    protected ?bool $prop = null;

    /**
     * @param bool $prop
     * 
     * @return void
     */
    public function setProp(bool $prop): void
    {
        $this->prop = $prop;
    }


    /**
     * 
     * @return bool
     */
    public function getProp(): bool
    {
        return $this->prop;
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
    public function jsonSerialize(): array
    {
        return [
            "prop" => $this->prop,
        ];
    }

}