<?php /** @noinspection PhpUndefinedMethodInspection */

declare(strict_types=1);

namespace Synatos\Porta\Model;

use Synatos\Porta\Contract\ArraySerializable;
use Synatos\Porta\Helper\ArrayHelper;

class ArraySerializableModel implements ArraySerializable
{

    /**
     * @var ModelProperty[]
     */
    private $propertyList;


    /**
     * ArraySerializableModel constructor.
     *
     * @param ModelProperty[] $propertyList
     */
    public function __construct(array $propertyList)
    {
        $this->propertyList = $propertyList;
    }


    /**
     * @param array $data
     */
    public function fromArray(array $data = null)
    {
        if ($data === null) {
            return;
        }
        foreach ($this->propertyList as $property) {
            $name = $property->getName();
            $propertyName = $property->getPropertyName();
            $propertyValue = isset($data[$name]) ? $data[$name] : null;

            if ($propertyValue === null) {
                $this->{$propertyName} = null;
                continue;
            }


            switch ($property->getType()) {
                case ModelProperty::TYPE_BUILD_IN:
                    $this->{$propertyName} = $propertyValue;
                    break;
                case ModelProperty::TYPE_OBJECT:
                    $this->fromArrayObject($property, $propertyValue);
                    break;
                case ModelProperty::TYPE_ARRAY:
                    $this->fromArrayObjectArray($property, $propertyValue);
                    break;
                case ModelProperty::TYPE_ASSOCIATIVE_ARRAY:
                    $this->fromArrayAssociativeArray($property, $propertyValue);
                    break;
            }
        }
    }


    /**
     * @param ModelProperty $property
     * @param $dataValue
     */
    private function fromArrayObject(ModelProperty $property, $dataValue)
    {
        $this->{$property->getName()} = $property->instantiate();
        $this->{$property->getName()}->fromArray($dataValue);
    }


    /**
     * @param ModelProperty $property
     * @param $dataValue
     */
    private function fromArrayObjectArray(ModelProperty $property, $dataValue)
    {
        $this->{$property->getName()} = [];
        foreach ($dataValue as $item) {
            $object = $property->instantiate();
            $object->fromArray($item);
            $this->{$property->getName()}[] = $object;
        }
    }


    /**
     * @param ModelProperty $property
     * @param $dataValue
     */
    private function fromArrayAssociativeArray(ModelProperty $property, $dataValue)
    {
        $this->{$property->getName()} = [];
        foreach ($dataValue as $key => $item) {
            $object = $property->instantiate();
            $object->fromArray($item);
            $this->{$property->getName()}[$key] = $object;
        }
    }


    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        $array = [];
        foreach ($this->propertyList as $property) {
            $name = $property->getName();
            $propertyName = $property->getPropertyName();
            $propertyValue = $this->{$propertyName};

            if ($propertyValue === null) {
                $array[$name] = null;
                continue;
            }

            switch ($property->getType()) {
                case ModelProperty::TYPE_BUILD_IN:
                    $array[$name] = $propertyValue;
                    break;
                case ModelProperty::TYPE_OBJECT:
                    $array[$name] = $propertyValue->jsonSerialize();
                    break;
                case ModelProperty::TYPE_ARRAY:
                    $array[$name] = [];
                    foreach ($this->{$name} as $item) {
                        $array[$name][] = $item->jsonSerialize();
                    }
                    break;
                case ModelProperty::TYPE_ASSOCIATIVE_ARRAY:
                    $array[$name] = [];
                    foreach ($this->{$name} as $key => $item) {
                        $array[$name][$key] = $item->jsonSerialize();
                    }
                    break;
            }
        }
        return ArrayHelper::filterEmpty($array);
    }


}