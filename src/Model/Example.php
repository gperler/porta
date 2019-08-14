<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Example extends ArraySerializableModel
{

    use ReferenceAble;

    /**
     * @var string|null
     */
    protected $summary;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $value;

    /**
     * @var string|null
     */
    protected $externalValue;


    /**
     * Example constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty('$ref', ModelProperty::TYPE_BUILD_IN, null, null, 'ref'),
            new ModelProperty("summary"),
            new ModelProperty("description"),
            new ModelProperty("value"),
            new ModelProperty("externalValue")
        ]);
    }


    /**
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }


    /**
     * @param string|null $summary
     */
    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }


    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }


    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }


    /**
     * @return string|null
     */
    public function getExternalValue(): ?string
    {
        return $this->externalValue;
    }


    /**
     * @param string|null $externalValue
     */
    public function setExternalValue(?string $externalValue): void
    {
        $this->externalValue = $externalValue;
    }


}