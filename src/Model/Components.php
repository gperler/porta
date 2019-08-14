<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Components extends ArraySerializableModel
{

    /**
     * @var Schema[]|null
     */
    protected $schemas;


    /**
     * @var Response[]|null
     */
    protected $responses;

    /**
     * @var Parameter[]|null
     */
    protected $parameters;

    /**
     * @var Example[]|null;
     */
    protected $examples;

    /**
     * @var RequestBody[]|null
     */
    protected $requestBodies;

    /**
     * @var Header[]|null
     */
    protected $headers;

    /**
     * @var SecurityScheme[]|null
     */
    protected $securitySchemes;

    /**
     * @var Link[]|null
     */
    protected $links;

    /**
     * @var Callback[]|null
     */
    protected $callbacks;


    /**
     * Components constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("schemas", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new Schema();
            }),
            new ModelProperty("responses", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new Response();
            }),
            new ModelProperty("parameters", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new Parameter();
            }),
            new ModelProperty("examples", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new Example();
            }),
            new ModelProperty("requestBodies", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new RequestBody();
            }),
            new ModelProperty("headers", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new Header();
            }),
            new ModelProperty("securitySchemes", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new SecurityScheme();
            }),
            new ModelProperty("links", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new Link();
            }),
            new ModelProperty("callbacks", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new Callback();
            })

        ]);
    }


    /**
     * @return Schema[]|null
     */
    public function getSchemas(): ?array
    {
        return $this->schemas;
    }


    /**
     * @param string $name
     *
     * @return Schema|null
     */
    public function getSchemaByName(string $name): ?Schema
    {
        return isset($this->schemas[$name]) ? $this->schemas[$name] : null;
    }


    /**
     * @param Schema[]|null $schemas
     */
    public function setSchemas(?array $schemas): void
    {
        $this->schemas = $schemas;
    }


    /**
     * @param string $name
     * @param Schema $schema
     */
    public function addSchema(string $name, Schema $schema): void
    {
        $this->schemas[$name] = $schema;
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
     * @return Example[]|null
     */
    public function getExamples(): ?array
    {
        return $this->examples;
    }


    /**
     * @param string $name
     *
     * @return Example|null
     */
    public function getExampleByName(string $name): ?Example
    {
        return isset($this->examples[$name]) ? $this->examples[$name] : null;
    }


    /**
     * @param Example[]|null $examples
     */
    public function setExamples(?array $examples): void
    {
        $this->examples = $examples;
    }


    /**
     * @return RequestBody[]|null
     */
    public function getRequestBodies(): ?array
    {
        return $this->requestBodies;
    }


    /**
     * @param RequestBody[]|null $requestBodies
     */
    public function setRequestBodies(?array $requestBodies): void
    {
        $this->requestBodies = $requestBodies;
    }


    /**
     * @return Header[]|null
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }


    /**
     * @param Header[]|null $headers
     */
    public function setHeaders(?array $headers): void
    {
        $this->headers = $headers;
    }


    /**
     * @return SecurityScheme[]|null
     */
    public function getSecuritySchemes(): ?array
    {
        return $this->securitySchemes;
    }


    /**
     * @param SecurityScheme[]|null $securitySchemes
     */
    public function setSecuritySchemes(?array $securitySchemes): void
    {
        $this->securitySchemes = $securitySchemes;
    }


    /**
     * @return Link[]|null
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }


    /**
     * @param Link[]|null $links
     */
    public function setLinks(?array $links): void
    {
        $this->links = $links;
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

}