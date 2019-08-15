<?php

declare(strict_types=1);

namespace Synatos\Porta\Http;

use JsonSerializable;

class RouteParameter implements JsonSerializable
{

    /**
     * @var string[]
     */
    private $valueList;


    /**
     * RouteParameter constructor.
     *
     * @param array $valueList
     */
    public function __construct(array $valueList)
    {
        $this->valueList = $valueList;
    }


    /**
     * @param string $name
     *
     * @return bool
     */
    public function isSet(string $name): bool
    {
        return array_key_exists($name, $this->valueList);
    }


    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getValue(string $name): ?string
    {
        return isset($this->valueList[$name]) ? $this->valueList[$name] : null;
    }


    /**
     * @return string[]
     */
    public function jsonSerialize()
    {
        return $this->valueList;
    }


}