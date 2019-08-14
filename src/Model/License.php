<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class License extends ArraySerializableModel
{

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $url;

    /**
     * License constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("name"),
            new ModelProperty("url")
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