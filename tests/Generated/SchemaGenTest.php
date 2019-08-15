<?php

declare(strict_types = 1);

namespace Synatos\PortaTest\Generated;

class SchemaGenTest implements \JsonSerializable
{

    const ENUM_VALUE_ASC = "ASC";

    const ENUM_VALUE_DESC = "DESC";

    /**
     * @var bool
     */
    protected $bool;

    /**
     * @var int
     */
    protected $int;

    /**
     * @var float
     */
    protected $float;

    /**
     * @var string
     */
    protected $string;

    /**
     * @var bool
     */
    protected $boolNullable;

    /**
     * @var int
     */
    protected $intNullable;

    /**
     * @var float
     */
    protected $floatNullable;

    /**
     * @var string
     */
    protected $stringNullable;

    /**
     * @var string
     */
    protected $enumValue;

    /**
     * @var SimpleObject
     */
    protected $simpleObject;

    /**
     * @var int[]
     */
    protected $primitiveArray;

    /**
     * @var ObjectArrayItem[]
     */
    protected $objectArray;

    /**
     * @var SchemaGenTestAdditionalProperties[]
     */
    protected $additionalProperties;

    /**
     * @param bool $bool
     * 
     * @return void
     */
    public function setBool(bool $bool)
    {
        $this->bool = $bool;
    }

    /**
     * 
     * @return bool
     */
    public function getBool() : bool
    {
        return $this->bool;
    }

    /**
     * @param int $int
     * 
     * @return void
     */
    public function setInt(int $int)
    {
        $this->int = $int;
    }

    /**
     * 
     * @return int
     */
    public function getInt() : int
    {
        return $this->int;
    }

    /**
     * @param float $float
     * 
     * @return void
     */
    public function setFloat(float $float)
    {
        $this->float = $float;
    }

    /**
     * 
     * @return float
     */
    public function getFloat() : float
    {
        return $this->float;
    }

    /**
     * @param string $string
     * 
     * @return void
     */
    public function setString(string $string)
    {
        $this->string = $string;
    }

    /**
     * 
     * @return string
     */
    public function getString() : string
    {
        return $this->string;
    }

    /**
     * @param bool|null $boolNullable
     * 
     * @return void
     */
    public function setBoolNullable(?bool $boolNullable)
    {
        $this->boolNullable = $boolNullable;
    }

    /**
     * 
     * @return bool|null
     */
    public function getBoolNullable() : ?bool
    {
        return $this->boolNullable;
    }

    /**
     * @param int|null $intNullable
     * 
     * @return void
     */
    public function setIntNullable(?int $intNullable)
    {
        $this->intNullable = $intNullable;
    }

    /**
     * 
     * @return int|null
     */
    public function getIntNullable() : ?int
    {
        return $this->intNullable;
    }

    /**
     * @param float|null $floatNullable
     * 
     * @return void
     */
    public function setFloatNullable(?float $floatNullable)
    {
        $this->floatNullable = $floatNullable;
    }

    /**
     * 
     * @return float|null
     */
    public function getFloatNullable() : ?float
    {
        return $this->floatNullable;
    }

    /**
     * @param string|null $stringNullable
     * 
     * @return void
     */
    public function setStringNullable(?string $stringNullable)
    {
        $this->stringNullable = $stringNullable;
    }

    /**
     * 
     * @return string|null
     */
    public function getStringNullable() : ?string
    {
        return $this->stringNullable;
    }

    /**
     * @param string $enumValue
     * 
     * @return void
     */
    public function setEnumValue(string $enumValue)
    {
        $this->enumValue = $enumValue;
    }

    /**
     * 
     * @return string
     */
    public function getEnumValue() : string
    {
        return $this->enumValue;
    }

    /**
     * 
     * @return void
     */
    public function setEnumValueAsc()
    {
        $this->enumValue = self::ENUM_VALUE_ASC;
    }

    /**
     * 
     * @return void
     */
    public function setEnumValueDesc()
    {
        $this->enumValue = self::ENUM_VALUE_DESC;
    }

    /**
     * 
     * @return bool
     */
    public function isEnumValueAsc() : bool
    {
        return $this->enumValue === self::ENUM_VALUE_ASC;
    }

    /**
     * 
     * @return bool
     */
    public function isEnumValueDesc() : bool
    {
        return $this->enumValue === self::ENUM_VALUE_DESC;
    }

    /**
     * @param SimpleObject $simpleObject
     * 
     * @return void
     */
    public function setSimpleObject(SimpleObject $simpleObject)
    {
        $this->simpleObject = $simpleObject;
    }

    /**
     * 
     * @return SimpleObject
     */
    public function getSimpleObject() : SimpleObject
    {
        return $this->simpleObject;
    }

    /**
     * @param int[] $primitiveArray
     * 
     * @return void
     */
    public function setPrimitiveArray(array $primitiveArray)
    {
        $this->primitiveArray = $primitiveArray;
    }

    /**
     * 
     * @return int[]
     */
    public function getPrimitiveArray() : array
    {
        return $this->primitiveArray;
    }

    /**
     * @param ObjectArrayItem[] $objectArray
     * 
     * @return void
     */
    public function setObjectArray(array $objectArray)
    {
        $this->objectArray = $objectArray;
    }

    /**
     * 
     * @return ObjectArrayItem[]
     */
    public function getObjectArray() : array
    {
        return $this->objectArray;
    }

    /**
     * @param SchemaGenTestAdditionalProperties[]|null $additionalProperties
     * 
     * @return void
     */
    public function setAdditionalProperties(?array $additionalProperties)
    {
        $this->additionalProperties = $additionalProperties;
    }

    /**
     * 
     * @return SchemaGenTestAdditionalProperties[]|null
     */
    public function getAdditionalProperties() : ?array
    {
        return $this->additionalProperties;
    }

    /**
     * @param array $array
     * 
     * @return void
     */
    public function fromArray(array $array)
    {
        $this->bool = $array["bool"];
        $this->int = $array["int"];
        $this->float = $array["float"];
        $this->string = $array["string"];
        $this->boolNullable = isset($array["boolNullable"]) ? $array["boolNullable"] : null;
        $this->intNullable = isset($array["intNullable"]) ? $array["intNullable"] : null;
        $this->floatNullable = isset($array["floatNullable"]) ? $array["floatNullable"] : null;
        $this->stringNullable = isset($array["stringNullable"]) ? $array["stringNullable"] : null;
        $this->enumValue = $array["enumValue"];
        if (isset($array["simpleObject"])) {
            $this->simpleObject = new SimpleObject();
            $this->simpleObject->fromArray($array["simpleObject"]);
        }
        $this->primitiveArray = $array["primitiveArray"];
        if (isset($array["objectArray"])) {
            foreach ($array["objectArray"] as $key => $item) {
                $itemObject = new ObjectArrayItem();
                $itemObject->fromArray($item);
                $this->objectArray[$key] = $itemObject;
            }
        }
    }

    /**
     * 
     * @return array
     */
    public function jsonSerialize() : array
    {
        $array = [
            "bool" => $this->bool,
            "int" => $this->int,
            "float" => $this->float,
            "string" => $this->string,
            "boolNullable" => $this->boolNullable,
            "intNullable" => $this->intNullable,
            "floatNullable" => $this->floatNullable,
            "stringNullable" => $this->stringNullable,
            "enumValue" => $this->enumValue,
            "simpleObject" => $this->simpleObject,
            "primitiveArray" => $this->primitiveArray,
            "objectArray" => $this->objectArray,
        ];
        return $array;
    }
}