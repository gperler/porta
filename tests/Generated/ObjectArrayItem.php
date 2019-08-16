<?php

declare(strict_types = 1);

namespace Synatos\PortaTest\Generated;

class ObjectArrayItem implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    /**
     * @param int $x
     * 
     * @return void
     */
    public function setX(int $x)
    {
        $this->x = $x;
    }

    /**
     * 
     * @return int
     */
    public function getX() : int
    {
        return $this->x;
    }

    /**
     * @param int $y
     * 
     * @return void
     */
    public function setY(int $y)
    {
        $this->y = $y;
    }

    /**
     * 
     * @return int
     */
    public function getY() : int
    {
        return $this->y;
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
                case "x":
                    $this->x = $propertyValue;
                    break;
                case "y":
                    $this->y = $propertyValue;
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
            "x" => $this->x,
            "y" => $this->y,
        ];
    }
}