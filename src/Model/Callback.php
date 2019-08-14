<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Callback extends ArraySerializableModel
{

    /**
     * @var PathItem[]|null
     */
    protected $paths;


    /**
     * Callback constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty('$ref'),
        ]);
    }


    /**
     * @param array $data
     */
    public function fromArray(array $data = null)
    {
        if ($data === null) {
            $this->paths = null;
            return;
        }

        parent::fromArray($data);

        $this->paths = [];
        foreach ($data as $path => $pathItemArray) {
            $pathItem = new PathItem();
            $pathItem->fromArray($pathItemArray);
            $this->paths[$path] = $pathItem;
        }
    }


    /**
     * @return array|PathItem[]|null
     */
    public function jsonSerialize()
    {
        if ($this->paths === null) {
            return null;
        }
        $array = parent::jsonSerialize();
        foreach ($this->paths as $path => $pathItem) {
            $array[$path] = $pathItem->jsonSerialize();
        }
        return $array;
    }


    /**
     * @return PathItem[]|null
     */
    public function getPaths(): ?array
    {
        return $this->paths;
    }


    /**
     * @param PathItem[]|null $paths
     */
    public function setPaths(?array $paths): void
    {
        $this->paths = $paths;
    }


}