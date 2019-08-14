<?php

declare(strict_types=1);

namespace Synatos\Porta\Http;

class Cookie
{

    /**
     * @var string[]
     */
    private $valueList;


    /**
     * Cookie constructor.
     *
     * @param string|null $value
     */
    public function __construct(?string $value)
    {
        if ($value === null) {
            return;
        }
        $value = strtr($value, [
            '&' => '%26',
            '+' => '%2B',
            ';' => '&']);
        parse_str($value, $this->valueList);
    }


    /**
     * @param string $name
     *
     * @return bool
     */
    public function isSet(string $name): bool
    {
        return isset($this->valueList[$name]);
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
}