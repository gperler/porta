<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class SecurityScheme extends ArraySerializableModel
{

    use ReferenceAble;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $in;

    /**
     * @var string|null
     */
    protected $scheme;

    /**
     * @var string|null
     */
    protected $bearerFormat;

    /**
     * @var OAuthFlows|null
     */
    protected $flows;

    /**
     * @var string|null
     */
    protected $openIdConnectUrl;


    /**
     * SecurityScheme constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty('$ref', ModelProperty::TYPE_BUILD_IN, null, null, 'ref'),
            new ModelProperty("type"),
            new ModelProperty("description"),
            new ModelProperty("name"),
            new ModelProperty("in"),
            new ModelProperty("scheme"),
            new ModelProperty("bearerFormat"),
            new ModelProperty("flows", ModelProperty::TYPE_OBJECT, static function () {
                return new OAuthFlows();
            }),
            new ModelProperty("openIdConnectUrl"),
        ]);
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getIn(): ?string
    {
        return $this->in;
    }

    /**
     * @param string|null $in
     */
    public function setIn(?string $in): void
    {
        $this->in = $in;
    }

    /**
     * @return string|null
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * @param string|null $scheme
     */
    public function setScheme(?string $scheme): void
    {
        $this->scheme = $scheme;
    }

    /**
     * @return string|null
     */
    public function getBearerFormat(): ?string
    {
        return $this->bearerFormat;
    }

    /**
     * @param string|null $bearerFormat
     */
    public function setBearerFormat(?string $bearerFormat): void
    {
        $this->bearerFormat = $bearerFormat;
    }

    /**
     * @return OAuthFlows|null
     */
    public function getFlows(): ?OAuthFlows
    {
        return $this->flows;
    }

    /**
     * @param OAuthFlows|null $flows
     */
    public function setFlows(?OAuthFlows $flows): void
    {
        $this->flows = $flows;
    }

    /**
     * @return string|null
     */
    public function getOpenIdConnectUrl(): ?string
    {
        return $this->openIdConnectUrl;
    }

    /**
     * @param string|null $openIdConnectUrl
     */
    public function setOpenIdConnectUrl(?string $openIdConnectUrl): void
    {
        $this->openIdConnectUrl = $openIdConnectUrl;
    }

}