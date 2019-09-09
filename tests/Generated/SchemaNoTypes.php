<?php

declare(strict_types = 1);

namespace Synatos\PortaTest\Generated;

class SchemaNoTypes implements \JsonSerializable
{

    /**
     * @var mixed
     */
    protected $bool;

    /**
     * @var array
     */
    protected $array;

    /**
     * @var array
     */
    protected $nullableArray;

    /**
     * @var array
     */
    protected $object;

    /**
     * @param mixed $bool
     * 
     * @return void
     */
    public function setBool($bool)
    {
        $this->bool = $bool;
    }

    /**
     * 
     * @return mixed
     */
    public function getBool()
    {
        return $this->bool;
    }

    /**
     * @param array $array
     * 
     * @return void
     */
    public function setArray(array $array)
    {
        $this->array = $array;
    }

    /**
     * 
     * @return array
     */
    public function getArray() : array
    {
        return $this->array;
    }

    /**
     * @param array|null $nullableArray
     * 
     * @return void
     */
    public function setNullableArray(?array $nullableArray)
    {
        $this->nullableArray = $nullableArray;
    }

    /**
     * 
     * @return array|null
     */
    public function getNullableArray() : ?array
    {
        return $this->nullableArray;
    }

    /**
     * @param array $object
     * 
     * @return void
     */
    public function setObject(array $object)
    {
        $this->object = $object;
    }

    /**
     * 
     * @return array
     */
    public function getObject() : array
    {
        return $this->object;
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
                case "bool":
                    $this->bool = $propertyValue;
                    break;
                case "array":
                    $this->array = $propertyValue;
                    break;
                case "nullableArray":
                    $this->nullableArray = $propertyValue;
                    break;
                case "object":
                    $this->object = $propertyValue;
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
            "bool" => $this->bool,
            "array" => $this->array,
            "nullableArray" => $this->nullableArray,
            "object" => $this->object,
        ];
    }
}