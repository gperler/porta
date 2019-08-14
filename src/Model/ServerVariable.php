<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class ServerVariable extends ArraySerializableModel
{

    /**
     * @var string[]|null
     */
    protected $enum;

    /**
     * @var string|null
     */
    protected $default;

    /**
     * @var string|null
     */
    protected $description;


    /**
     * ServerVariable constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("enum"),
            new ModelProperty("default"),
            new ModelProperty("description"),
        ]);
    }


    /**
     * @return string[]|null
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }


    /**
     * @param string[]|null $enum
     */
    public function setEnum(?array $enum): void
    {
        $this->enum = $enum;
    }


    /**
     * @return string|null
     */
    public function getDefault(): ?string
    {
        return $this->default;
    }


    /**
     * @param string|null $default
     */
    public function setDefault(?string $default): void
    {
        $this->default = $default;
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


}