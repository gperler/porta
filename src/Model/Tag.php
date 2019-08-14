<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Tag extends ArraySerializableModel
{

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var ExternalDocumentation|null
     */
    protected $externalDocs;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("name"),
            new ModelProperty("description"),
            new ModelProperty("externalDocs", ModelProperty::TYPE_OBJECT, function () {
                return new ExternalDocumentation();
            }),
        ]);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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
     * @return ExternalDocumentation|null
     */
    public function getExternalDocs(): ?ExternalDocumentation
    {
        return $this->externalDocs;
    }

    /**
     * @param ExternalDocumentation|null $externalDocs
     */
    public function setExternalDocs(?ExternalDocumentation $externalDocs): void
    {
        $this->externalDocs = $externalDocs;
    }


}