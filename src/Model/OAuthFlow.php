<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class OAuthFlow extends ArraySerializableModel
{

    /**
     * @var string|null
     */
    protected $authorizationUrl;

    /**
     * @var string|null
     */
    protected $tokenUrl;

    /**
     * @var string|null
     */
    protected $refreshUrl;

    /**
     * @var string[]|null
     */
    protected $scopes;

    /**
     * OAuthFlow constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("authorizationUrl"),
            new ModelProperty("tokenUrl"),
            new ModelProperty("refreshUrl"),
            new ModelProperty("scopes")
        ]);
    }

    /**
     * @return string|null
     */
    public function getAuthorizationUrl(): ?string
    {
        return $this->authorizationUrl;
    }

    /**
     * @param string|null $authorizationUrl
     */
    public function setAuthorizationUrl(?string $authorizationUrl): void
    {
        $this->authorizationUrl = $authorizationUrl;
    }

    /**
     * @return string|null
     */
    public function getTokenUrl(): ?string
    {
        return $this->tokenUrl;
    }

    /**
     * @param string|null $tokenUrl
     */
    public function setTokenUrl(?string $tokenUrl): void
    {
        $this->tokenUrl = $tokenUrl;
    }

    /**
     * @return string|null
     */
    public function getRefreshUrl(): ?string
    {
        return $this->refreshUrl;
    }

    /**
     * @param string|null $refreshUrl
     */
    public function setRefreshUrl(?string $refreshUrl): void
    {
        $this->refreshUrl = $refreshUrl;
    }

    /**
     * @return string[]|null
     */
    public function getScopes(): ?array
    {
        return $this->scopes;
    }

    /**
     * @param string[]|null $scopes
     */
    public function setScopes(?array $scopes): void
    {
        $this->scopes = $scopes;
    }
}