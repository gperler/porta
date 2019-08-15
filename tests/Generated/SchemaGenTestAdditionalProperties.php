<?php

declare(strict_types = 1);

namespace Synatos\PortaTest\Generated;

class SchemaGenTestAdditionalProperties implements \JsonSerializable
{

    /**
     * @var bool
     */
    protected $p1;

    /**
     * @param bool $p1
     * 
     * @return void
     */
    public function setP1(bool $p1)
    {
        $this->p1 = $p1;
    }

    /**
     * 
     * @return bool
     */
    public function getP1() : bool
    {
        return $this->p1;
    }

    /**
     * @param array $array
     * 
     * @return void
     */
    public function fromArray(array $array)
    {
        $this->p1 = $array["p1"];
    }

    /**
     * 
     * @return array
     */
    public function jsonSerialize() : array
    {
        $array = [
            "p1" => $this->p1,
        ];
        return $array;
    }
}