<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class PathItem extends ArraySerializableModel
{

    const METHOD_GET = "get";

    const METHOD_PUT = "put";

    const METHOD_POST = "post";

    const METHOD_DELETE = "delete";

    const METHOD_OPTIONS = "options";

    const METHOD_HEAD = "head";

    const METHOD_PATCH = "patch";

    const METHOD_TRACE = "trace";


    /**
     * @var string|null
     */
    protected $summary;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var Operation|null
     */
    protected $get;

    /**
     * @var Operation|null
     */
    protected $put;

    /**
     * @var Operation|null
     */
    protected $post;

    /**
     * @var Operation|null
     */
    protected $delete;

    /**
     * @var Operation|null
     */
    protected $options;

    /**
     * @var Operation|null
     */
    protected $head;

    /**
     * @var Operation|null
     */
    protected $patch;

    /**
     * @var Operation|null
     */
    protected $trace;

    /**
     * @var Server[]
     */
    protected $servers;


    /**
     * @var Parameter[]
     */
    protected $parameters;


    /**
     * Path constructor.
     */
    public function __construct()
    {
        $createOperation = function () {
            return new Operation();
        };

        parent::__construct([
            new ModelProperty("summary"),
            new ModelProperty("description"),
            new ModelProperty("get", ModelProperty::TYPE_OBJECT, $createOperation),
            new ModelProperty("put", ModelProperty::TYPE_OBJECT, $createOperation),
            new ModelProperty("post", ModelProperty::TYPE_OBJECT, $createOperation),
            new ModelProperty("delete", ModelProperty::TYPE_OBJECT, $createOperation),
            new ModelProperty("options", ModelProperty::TYPE_OBJECT, $createOperation),
            new ModelProperty("head", ModelProperty::TYPE_OBJECT, $createOperation),
            new ModelProperty("patch", ModelProperty::TYPE_OBJECT, $createOperation),
            new ModelProperty("trace", ModelProperty::TYPE_OBJECT, $createOperation),
            new ModelProperty("servers", ModelProperty::TYPE_ARRAY, function () {
                return new Server();
            }),
            new ModelProperty("parameters", ModelProperty::TYPE_ARRAY, function () {
                return new Parameter();
            }),
        ]);
        $this->servers = [];
    }


    /**
     * @param string $method
     *
     * @return Operation|null
     */
    public function getOperationByMethod(string $method): ?Operation
    {
        $method = strtolower($method);
        switch ($method) {
            case self::METHOD_GET:
                return $this->get;
            case self::METHOD_PUT:
                return $this->put;
            case self::METHOD_POST:
                return $this->post;
            case self::METHOD_DELETE:
                return $this->delete;
            case self::METHOD_OPTIONS:
                return $this->options;
            case self::METHOD_HEAD:
                return $this->head;
            case self::METHOD_PATCH:
                return $this->patch;
            case self::METHOD_TRACE:
                return $this->trace;
            default:
                return null;
        }
    }


    /**
     * @param string $method
     * @param Operation $operation
     */
    public function setOperationByMethod(string $method, Operation $operation)
    {
        $method = strtolower($method);
        switch ($method) {
            case self::METHOD_GET:
                $this->get = $operation;
                break;
            case self::METHOD_PUT:
                $this->put = $operation;
                break;
            case self::METHOD_POST:
                $this->post = $operation;
                break;
            case self::METHOD_DELETE:
                $this->delete = $operation;
                break;
            case self::METHOD_OPTIONS:
                $this->options = $operation;
                break;
            case self::METHOD_HEAD:
                $this->head = $operation;
                break;
            case self::METHOD_PATCH:
                $this->patch = $operation;
                break;
            case self::METHOD_TRACE:
                $this->trace = $operation;
                break;
        }
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
     * @return Operation|null
     */
    public function getGet(): ?Operation
    {
        return $this->get;
    }


    /**
     * @param Operation|null $get
     */
    public function setGet(?Operation $get): void
    {
        $this->get = $get;
    }


    /**
     * @return Operation|null
     */
    public function getPut(): ?Operation
    {
        return $this->put;
    }


    /**
     * @param Operation|null $put
     */
    public function setPut(?Operation $put): void
    {
        $this->put = $put;
    }


    /**
     * @return Operation|null
     */
    public function getPost(): ?Operation
    {
        return $this->post;
    }


    /**
     * @param Operation|null $post
     */
    public function setPost(?Operation $post): void
    {
        $this->post = $post;
    }


    /**
     * @return Operation|null
     */
    public function getDelete(): ?Operation
    {
        return $this->delete;
    }


    /**
     * @param Operation|null $delete
     */
    public function setDelete(?Operation $delete): void
    {
        $this->delete = $delete;
    }


    /**
     * @return Operation|null
     */
    public function getOptions(): ?Operation
    {
        return $this->options;
    }


    /**
     * @param Operation|null $options
     */
    public function setOptions(?Operation $options): void
    {
        $this->options = $options;
    }


    /**
     * @return Operation|null
     */
    public function getHead(): ?Operation
    {
        return $this->head;
    }


    /**
     * @param Operation|null $head
     */
    public function setHead(?Operation $head): void
    {
        $this->head = $head;
    }


    /**
     * @return Operation|null
     */
    public function getPatch(): ?Operation
    {
        return $this->patch;
    }


    /**
     * @param Operation|null $patch
     */
    public function setPatch(?Operation $patch): void
    {
        $this->patch = $patch;
    }


    /**
     * @return Operation|null
     */
    public function getTrace(): ?Operation
    {
        return $this->trace;
    }


    /**
     * @param Operation|null $trace
     */
    public function setTrace(?Operation $trace): void
    {
        $this->trace = $trace;
    }


    /**
     * @return Server[]
     */
    public function getServers(): array
    {
        return $this->servers;
    }


    /**
     * @param Server[] $servers
     */
    public function setServers(array $servers): void
    {
        $this->servers = $servers;
    }


    /**
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }


    /**
     * @param Parameter[] $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }


}