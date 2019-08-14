<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Operation extends ArraySerializableModel
{

    /**
     * @var string[]|null
     */
    protected $tags;

    /**
     * @var string|null
     */
    protected $summary;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var ExternalDocumentation
     */
    protected $externalDocs;

    /**
     * @var string|null
     */
    protected $operationId;

    /**
     * @var Parameter[]|null
     */
    protected $parameters;

    /**
     * @var RequestBody|null
     */
    protected $requestBody;

    /**
     * @var Response[]|null
     */
    protected $responses;

    /**
     * @var Callback[]|null
     */
    protected $callbacks;

    /**
     * @var bool
     */
    protected $deprecated;

    /**
     * @var SecurityRequirement[]|null
     */
    protected $security;

    /**
     * @var Server|null
     */
    protected $servers;


    /**
     * Operation constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("tags"),
            new ModelProperty("summary"),
            new ModelProperty("description"),
            new ModelProperty("externalDocs", ModelProperty::TYPE_OBJECT, function () {
                return new ExternalDocumentation();
            }),
            new ModelProperty("operationId"),
            new ModelProperty("parameters", ModelProperty::TYPE_ARRAY, function () {
                return new Parameter();
            }),
            new ModelProperty("requestBody", ModelProperty::TYPE_OBJECT, function () {
                return new RequestBody();
            }),
            new ModelProperty("responses", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, function () {
                return new Response();
            }),
            new ModelProperty("callbacks", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, function () {
                return new Callback();
            }),
            new ModelProperty("deprecated"),
            new ModelProperty("security", ModelProperty::TYPE_ARRAY, function () {
                return new SecurityRequirement();
            }),
            new ModelProperty("servers", ModelProperty::TYPE_ARRAY, function () {
                return new Server();
            }),
        ]);
    }


    /**
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }


    /**
     * @param string|null $summary
     */
    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
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
     * @return string[]|null
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }


    /**
     * @param string[]|null $tags
     */
    public function setTags(?array $tags): void
    {
        $this->tags = $tags;
    }


    /**
     * @return ExternalDocumentation
     */
    public function getExternalDocs(): ExternalDocumentation
    {
        return $this->externalDocs;
    }


    /**
     * @param ExternalDocumentation $externalDocs
     */
    public function setExternalDocs(ExternalDocumentation $externalDocs): void
    {
        $this->externalDocs = $externalDocs;
    }


    /**
     * @return string|null
     */
    public function getOperationId(): ?string
    {
        return $this->operationId;
    }


    /**
     * @param string|null $operationId
     */
    public function setOperationId(?string $operationId): void
    {
        $this->operationId = $operationId;
    }


    /**
     * @return Parameter[]|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }


    /**
     * @param Parameter[]|null $parameters
     */
    public function setParameters(?array $parameters): void
    {
        $this->parameters = $parameters;
    }


    /**
     * @return RequestBody|null
     */
    public function getRequestBody(): ?RequestBody
    {
        return $this->requestBody;
    }


    /**
     * @param RequestBody|null $requestBody
     */
    public function setRequestBody(?RequestBody $requestBody): void
    {
        $this->requestBody = $requestBody;
    }


    /**
     * @return Response[]|null
     */
    public function getResponses(): ?array
    {
        return $this->responses;
    }


    /**
     * @param Response[]|null $responses
     */
    public function setResponses(?array $responses): void
    {
        $this->responses = $responses;
    }


    /**
     * @param string $statusCode
     * @param Response $response
     */
    public function addResponse(string $statusCode, Response $response)
    {
        if ($this->responses === null) {
            $this->responses = [];
        }
        $this->responses[$statusCode] = $response;
    }


    /**
     * @return Callback[]|null
     */
    public function getCallbacks(): ?array
    {
        return $this->callbacks;
    }


    /**
     * @param Callback[]|null $callbacks
     */
    public function setCallbacks(?array $callbacks): void
    {
        $this->callbacks = $callbacks;
    }


    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }


    /**
     * @param bool $deprecated
     */
    public function setDeprecated(bool $deprecated): void
    {
        $this->deprecated = $deprecated;
    }


    /**
     * @return SecurityRequirement[]|null
     */
    public function getSecurity(): ?array
    {
        return $this->security;
    }


    /**
     * @param SecurityRequirement[]|null $security
     */
    public function setSecurity(?array $security): void
    {
        $this->security = $security;
    }


    /**
     * @return Server|null
     */
    public function getServers(): ?Server
    {
        return $this->servers;
    }


    /**
     * @param Server|null $servers
     */
    public function setServers(?Server $servers): void
    {
        $this->servers = $servers;
    }


}