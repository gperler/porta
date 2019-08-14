<?php

declare(strict_types=1);

namespace Synatos\Porta\Http;

class HttpResponse
{

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var HttpHeader
     */
    private $header;

    /**
     * @var array|null
     */
    private $responseBody;


    /**
     * HttpResponse constructor.
     *
     * @param int $statusCode
     * @param array $responseHeader
     * @param array|null $responseBody
     */
    public function __construct(int $statusCode, array $responseHeader, array $responseBody = null)
    {
        $this->statusCode = $statusCode;
        $this->header = new HttpHeader($responseHeader);
        $this->responseBody = $responseBody;
    }


    public function getContentType(): ?string
    {
        return $this->header->getContentType();
    }


    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }


    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }


    /**
     * @return HttpHeader
     */
    public function getHeader(): HttpHeader
    {
        return $this->header;
    }


    /**
     * @param HttpHeader $header
     */
    public function setHeader(HttpHeader $header): void
    {
        $this->header = $header;
    }


    /**
     * @return array|null
     */
    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }


    /**
     * @param array|null $responseBody
     */
    public function setResponseBody(?array $responseBody): void
    {
        $this->responseBody = $responseBody;
    }


}