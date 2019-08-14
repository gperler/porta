<?php

declare(strict_types=1);

namespace Synatos\Porta\Http;

class PathParameter
{

    /**
     * @var string[]
     */
    private $parameter;


    /**
     * @param string $name
     * @return bool
     */
    public function isSet(string $name): bool
    {
        return isset($this->parameter[$name]);
    }


    /**
     * @param string $name
     * @return string|null
     */
    public function getValue(string $name): ?string
    {
        return isset($this->parameter[$name]) ? $this->parameter[$name] : null;
    }
}