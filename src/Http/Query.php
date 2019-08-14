<?php

declare(strict_types=1);

namespace Synatos\Porta\Http;

class Query
{

    /**
     * @var array
     */
    private $query;


    /**
     * Header constructor.
     * @param array $query
     */
    public function __construct(array $query)
    {
        $this->query = [];
        foreach ($query as $name => $value) {
            $this->query[$name] = $value;
        }
    }


    /**
     * @param string $name
     * @return bool
     */
    public function isSet(string $name): bool
    {
        return isset($this->query[$name]);
    }


    /**
     * @param string $name
     * @return string|null
     */
    public function getValue(string $name): ?string
    {
        return isset($this->query[$name]) ? $this->query[$name] : null;
    }

}