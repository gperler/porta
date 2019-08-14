<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Link extends ArraySerializableModel
{

    use ReferenceAble;

    /**
     * @var string|null
     */
    protected $operationRef;

    /**
     * @var string|null
     */
    protected $operationId;

    /**
     * @var string[]|null
     */
    protected $parameters;

    /**
     * @var mixed|null
     */
    protected $requestBody;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var Server|null
     */
    protected $server;

    /**
     * Link constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty('$ref', ModelProperty::TYPE_BUILD_IN, null, null, 'ref'),
            new ModelProperty("operationRef"),
            new ModelProperty("operationId"),
            new ModelProperty("parameters"),
            new ModelProperty("requestBody", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new RequestBody();
            }),
            new ModelProperty("description"),
            new ModelProperty("server", ModelProperty::TYPE_OBJECT, function () {
                return new Server();
            }),

        ]);
    }

    /**
     * @return string|null
     */
    public function getOperationRef(): ?string
    {
        return $this->operationRef;
    }

    /**
     * @param string|null $operationRef
     */
    public function setOperationRef(?string $operationRef): void
    {
        $this->operationRef = $operationRef;
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
     * @return string[]|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @param string[]|null $parameters
     */
    public function setParameters(?array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return mixed|null
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * @param mixed|null $requestBody
     */
    public function setRequestBody($requestBody): void
    {
        $this->requestBody = $requestBody;
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
     * @return Server|null
     */
    public function getServer(): ?Server
    {
        return $this->server;
    }

    /**
     * @param Server|null $server
     */
    public function setServer(?Server $server): void
    {
        $this->server = $server;
    }


}