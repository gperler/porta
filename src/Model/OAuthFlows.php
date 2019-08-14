<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class OAuthFlows extends ArraySerializableModel
{

    /**
     * @var OAuthFlow[]|null
     */
    protected $implicit;

    /**
     * @var OAuthFlow[]|null
     */
    protected $password;

    /**
     * @var OAuthFlow[]|null
     */
    protected $clientCredentials;

    /**
     * @var OAuthFlow[]|null
     */
    protected $authorizationCode;


    /**
     * OAuthFlows constructor.
     */
    public function __construct()
    {
        $oauthFlowConstructor = function () {
            return new OAuthFlow();
        };
        parent::__construct([
            new ModelProperty("implicit", ModelProperty::TYPE_OBJECT, $oauthFlowConstructor),
            new ModelProperty("password", ModelProperty::TYPE_OBJECT, $oauthFlowConstructor),
            new ModelProperty("clientCredentials", ModelProperty::TYPE_OBJECT, $oauthFlowConstructor),
            new ModelProperty("authorizationCode", ModelProperty::TYPE_OBJECT, $oauthFlowConstructor),
        ]);
    }

    /**
     * @return OAuthFlow[]|null
     */
    public function getImplicit(): ?array
    {
        return $this->implicit;
    }

    /**
     * @param OAuthFlow[]|null $implicit
     */
    public function setImplicit(?array $implicit): void
    {
        $this->implicit = $implicit;
    }

    /**
     * @return OAuthFlow[]|null
     */
    public function getPassword(): ?array
    {
        return $this->password;
    }

    /**
     * @param OAuthFlow[]|null $password
     */
    public function setPassword(?array $password): void
    {
        $this->password = $password;
    }

    /**
     * @return OAuthFlow[]|null
     */
    public function getClientCredentials(): ?array
    {
        return $this->clientCredentials;
    }

    /**
     * @param OAuthFlow[]|null $clientCredentials
     */
    public function setClientCredentials(?array $clientCredentials): void
    {
        $this->clientCredentials = $clientCredentials;
    }

    /**
     * @return OAuthFlow[]|null
     */
    public function getAuthorizationCode(): ?array
    {
        return $this->authorizationCode;
    }

    /**
     * @param OAuthFlow[]|null $authorizationCode
     */
    public function setAuthorizationCode(?array $authorizationCode): void
    {
        $this->authorizationCode = $authorizationCode;
    }

}