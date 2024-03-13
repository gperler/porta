<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class SecurityRequirement extends ArraySerializableModel
{
    /**
     * @var string[]|null
     */
    protected $requirements;


    /**
     * SecurityRequirement constructor.
     */
    public function __construct()
    {
        parent::__construct([]);
    }


    /**
     * @param array $data
     */
    public function fromArray(array $data = null)
    {
        $this->requirements = $data;
    }


    /**
     * @return array|PathItem[]|null
     */
    public function jsonSerialize(): mixed
    {
        return $this->requirements;
    }

}