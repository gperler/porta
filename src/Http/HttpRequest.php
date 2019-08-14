<?php

declare(strict_types=1);

namespace Synatos\Porta\Http;

class HttpRequest
{
    /**
     * @var string
     */
    private $path;


    /**
     * @var string
     */
    private $method;

    /**
     * @var HttpHeader
     */
    private $header;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var RouteParameter
     */
    private $routeParameter;

    /**
     * @var string
     */
    private $requestBody;


    /**
     * HttpRequest constructor.
     *
     * @param string $path
     * @param string $method
     * @param array $header
     * @param array $routeParameter
     * @param array $query
     * @param string|null $requestBody
     */
    public function __construct(string $path, string $method, array $header = [], array $routeParameter = [], array $query = [], string $requestBody = null)
    {
        $this->path = $path;
        $this->method = $method;
        $this->header = new HttpHeader($header);
        $this->routeParameter = new RouteParameter($routeParameter);
        $this->query = new Query($query);
        $this->requestBody = $requestBody;
    }


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }


    /**
     * @return HttpHeader
     */
    public function getHeader(): HttpHeader
    {
        return $this->header;
    }


    /**
     * @return string|null
     */
    public function getContentType(): ?string
    {
        return $this->header->getContentType();
    }


    /**
     * @return Cookie
     */
    public function getCookie(): Cookie
    {
        return $this->header->getCookie();
    }


    /**
     * @return RouteParameter
     */
    public function getRouteParameter(): RouteParameter
    {
        return $this->routeParameter;
    }


    /**
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }


    /**
     * @return string
     */
    public function getRequestBody(): string
    {
        return $this->requestBody;
    }


    /**
     * @return array|null
     */
    public function getRequestBodyJSON(): ?array
    {
        if ($this->requestBody === null) {
            return null;
        }
        $array = json_decode($this->requestBody, true);

        $error = json_last_error();

        if ($error === JSON_ERROR_NONE) {
            return $array;
        }
        return null;
    }

}