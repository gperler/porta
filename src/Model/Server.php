<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Server extends ArraySerializableModel
{
    /**
     * @var string|null
     */
    protected $url;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var ServerVariable[]
     */
    protected $variables;


    /**
     * Server constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("url"),
            new ModelProperty("description"),
            new ModelProperty("variables", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, function () {
                return new ServerVariable();
            })
        ]);
        $this->variables = [];
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
     * @return ServerVariable[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }


    /**
     * @param ServerVariable[] $variables
     */
    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }


    /**
     * @param string $name
     * @param ServerVariable $serverVariable
     */
    public function addVariable(string $name, ServerVariable $serverVariable)
    {
        $this->variables[$name] = $serverVariable;
    }

}