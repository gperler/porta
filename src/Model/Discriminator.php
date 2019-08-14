<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Discriminator extends ArraySerializableModel
{

    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var array|null
     */
    protected $mapping;


    /**
     * Discriminator constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("propertyName"),
            new ModelProperty("mapping")
        ]);
    }

    /**
     * @param string $discriminatorValue
     * @return Reference|null
     */
    public function getMappingReference(string $discriminatorValue): ?string
    {
        if ($this->mapping === null || !isset($this->mapping[$discriminatorValue])) {
            return null;
        }
        return $this->mapping[$discriminatorValue];
    }


    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName
     */
    public function setPropertyName(string $propertyName): void
    {
        $this->propertyName = $propertyName;
    }

    /**
     * @return array|null
     */
    public function getMapping(): ?array
    {
        return $this->mapping;
    }

    /**
     * @param array|null $mapping
     */
    public function setMapping(?array $mapping): void
    {
        $this->mapping = $mapping;
    }


}