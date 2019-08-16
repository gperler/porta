<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Model\Asset;

use Synatos\Porta\Model\ArraySerializableModel;
use Synatos\Porta\Model\ModelProperty;
use Synatos\Porta\Model\ReferenceAble;

class MainObject extends ArraySerializableModel
{

    use ReferenceAble;

    /**
     * @var int
     */
    protected $buildIn;


    /**
     * @var int
     */
    protected $zero;

    /**
     * @var NestedObject
     */
    protected $nestedObject;


    /**
     * @var NestedObject[]
     */
    protected $nestedArrayObject;


    /**
     * @var NestedObject[]
     */
    protected $nestedAssociativeArrayObject;


    /**
     * MainObject constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty('$ref', ModelProperty::TYPE_BUILD_IN, null, null, 'ref'),
            new ModelProperty("buildIn"),
            new ModelProperty("zero"),
            new ModelProperty("nestedObject", ModelProperty::TYPE_OBJECT, function () {
                return new NestedObject();
            }),
            new ModelProperty("nestedArrayObject", ModelProperty::TYPE_ARRAY, function () {
                return new NestedObject();
            }),
            new ModelProperty("nestedAssociativeArrayObject", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, function () {
                return new NestedObject();
            })
        ]);
    }

    /**
     * @return int
     */
    public function getBuildIn(): int
    {
        return $this->buildIn;
    }

    /**
     * @param int $buildIn
     */
    public function setBuildIn(int $buildIn): void
    {
        $this->buildIn = $buildIn;
    }

    /**
     * @return int
     */
    public function getZero(): int
    {
        return $this->zero;
    }

    /**
     * @param int $zero
     */
    public function setZero(int $zero): void
    {
        $this->zero = $zero;
    }

    /**
     * @return NestedObject
     */
    public function getNestedObject(): NestedObject
    {
        return $this->nestedObject;
    }

    /**
     * @param NestedObject $nestedObject
     */
    public function setNestedObject(NestedObject $nestedObject): void
    {
        $this->nestedObject = $nestedObject;
    }

    /**
     * @return NestedObject[]
     */
    public function getNestedArrayObject(): array
    {
        return $this->nestedArrayObject;
    }

    /**
     * @param NestedObject[] $nestedArrayObject
     */
    public function setNestedArrayObject(array $nestedArrayObject): void
    {
        $this->nestedArrayObject = $nestedArrayObject;
    }

    /**
     * @return NestedObject[]
     */
    public function getNestedAssociativeArrayObject(): array
    {
        return $this->nestedAssociativeArrayObject;
    }

    /**
     * @param NestedObject[] $nestedAssociativeArrayObject
     */
    public function setNestedAssociativeArrayObject(array $nestedAssociativeArrayObject): void
    {
        $this->nestedAssociativeArrayObject = $nestedAssociativeArrayObject;
    }


}