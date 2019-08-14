<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Header extends ArraySerializableModel
{

    use ReferenceAble;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var bool|null
     */
    protected $required;

    /**
     * @var bool|null
     */
    protected $deprecated;

    /**
     * @var bool|null
     */
    protected $allowEmptyValue;

    /**
     * @var string|null
     */
    protected $style;

    /**
     * @var bool|null
     */
    protected $explode;

    /**
     * @var bool|null
     */
    protected $allowReserved;

    /**
     * @var Schema|null
     */
    protected $schema;

    /**
     * @var mixed|null
     */
    protected $example;

    /**
     * @var Example[]|null
     */
    protected $examples;


    public function __construct()
    {
        parent::__construct([
            new ModelProperty('$ref', ModelProperty::TYPE_BUILD_IN, null, null, 'ref'),
            new ModelProperty("description"),
            new ModelProperty("required"),
            new ModelProperty("deprecated"),
            new ModelProperty("allowEmptyValue"),
            new ModelProperty("style"),
            new ModelProperty("explode"),
            new ModelProperty("allowReserved"),
            new ModelProperty("schema", ModelProperty::TYPE_OBJECT, function () {
                return new Schema();
            }),
            new ModelProperty("example"),
            new ModelProperty("examples", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, function () {
                return new Example();
            })
        ]);
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
     * @return bool|null
     */
    public function getRequired(): ?bool
    {
        return $this->required;
    }


    /**
     * @param bool|null $required
     */
    public function setRequired(?bool $required): void
    {
        $this->required = $required;
    }


    /**
     * @return bool|null
     */
    public function getDeprecated(): ?bool
    {
        return $this->deprecated;
    }


    /**
     * @param bool|null $deprecated
     */
    public function setDeprecated(?bool $deprecated): void
    {
        $this->deprecated = $deprecated;
    }


    /**
     * @return bool|null
     */
    public function getAllowEmptyValue(): ?bool
    {
        return $this->allowEmptyValue;
    }


    /**
     * @param bool|null $allowEmptyValue
     */
    public function setAllowEmptyValue(?bool $allowEmptyValue): void
    {
        $this->allowEmptyValue = $allowEmptyValue;
    }


    /**
     * @return string|null
     */
    public function getStyle(): ?string
    {
        return $this->style;
    }


    /**
     * @param string|null $style
     */
    public function setStyle(?string $style): void
    {
        $this->style = $style;
    }


    /**
     * @return bool|null
     */
    public function getExplode(): ?bool
    {
        return $this->explode;
    }


    /**
     * @param bool|null $explode
     */
    public function setExplode(?bool $explode): void
    {
        $this->explode = $explode;
    }


    /**
     * @return bool|null
     */
    public function getAllowReserved(): ?bool
    {
        return $this->allowReserved;
    }


    /**
     * @param bool|null $allowReserved
     */
    public function setAllowReserved(?bool $allowReserved): void
    {
        $this->allowReserved = $allowReserved;
    }


    /**
     * @return Schema|null
     */
    public function getSchema(): ?Schema
    {
        return $this->schema;
    }


    /**
     * @param Schema|null $schema
     */
    public function setSchema(?Schema $schema): void
    {
        $this->schema = $schema;
    }


    /**
     * @return mixed|null
     */
    public function getExample()
    {
        return $this->example;
    }


    /**
     * @param mixed|null $example
     */
    public function setExample($example = null): void
    {
        $this->example = $example;
    }


    /**
     * @return Example[]|null
     */
    public function getExamples(): ?array
    {
        return $this->examples;
    }


    /**
     * @param Example[]|null $examples
     */
    public function setExamples(?array $examples): void
    {
        $this->examples = $examples;
    }


}