<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class ExternalDocumentation extends ArraySerializableModel
{

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $url;


    /**
     * ExternalDocumentation constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("description"),
            new ModelProperty("url"),
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
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }


    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }


}