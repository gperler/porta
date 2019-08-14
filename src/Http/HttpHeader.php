<?php

declare(strict_types=1);

namespace Synatos\Porta\Http;

class HttpHeader
{

    const COOKIE_HEADER = "cookie";

    const CONTENT_TYPE = "content-type";

    /**
     * @var array
     */
    private $header;


    /**
     * Header constructor.
     *
     * @param array $header
     */
    public function __construct(array $header)
    {
        $this->header = [];
        foreach ($header as $name => $value) {
            $this->header[strtolower($name)] = $value;
        }
    }


    /**
     * @param string $name
     *
     * @return bool
     */
    public function isSet(string $name): bool
    {
        $name = strtolower($name);
        return array_key_exists($name, $this->header);
    }


    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getValue(string $name): ?string
    {
        $name = strtolower($name);
        return isset($this->header[$name]) ? $this->header[$name] : null;
    }


    /**
     * @return Cookie|null
     */
    public function getCookie(): ?Cookie
    {
        $cookie = $this->getValue(self::COOKIE_HEADER);
        return $cookie !== null ? new Cookie($cookie) : null;
    }


    /**
     * @return string|null
     */
    public function getContentType(): ?string
    {
        return $this->getValue(self::CONTENT_TYPE);
    }

}