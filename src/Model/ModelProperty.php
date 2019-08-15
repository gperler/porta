<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

use Closure;
use Synatos\Porta\Contract\ArraySerializable;

class ModelProperty
{

    const TYPE_BUILD_IN = 'build_in';

    const TYPE_OBJECT = 'type_object';

    const TYPE_ARRAY = 'type_array';

    const TYPE_ASSOCIATIVE_ARRAY = 'associative_array';

    /**
     * Name in Array
     * @var string
     */
    private $name;

    /**
     * Name in Object (needed for $ref vs ref)
     * @var string
     */
    private $propertyName;


    /**
     * @var Closure
     */
    private $factoryFunction;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $defaultValue;


    /**
     * ModelProperty constructor.
     * @param string $name
     * @param string $type
     * @param Closure|null $factoryFunction
     * @param null $defaultValue
     * @param string|null $propertyName
     */
    public function __construct(string $name, string $type = self::TYPE_BUILD_IN, Closure $factoryFunction = null, $defaultValue = null, string $propertyName = null)
    {
        $this->name = $name;
        $this->factoryFunction = $factoryFunction;
        $this->type = $type;
        $this->defaultValue = $defaultValue;
        $this->propertyName = $propertyName !== null ? $propertyName : $name;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    
    /**
     * @return ArraySerializable
     */
    public function instantiate(): ArraySerializable
    {
        return ($this->factoryFunction)();
    }


    /**
     * @return bool
     */
    public function isArraySerializable(): bool
    {
        return $this->factoryFunction !== null;
    }


    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}