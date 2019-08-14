<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Encoding extends ArraySerializableModel
{

    /**
     * @var string|null
     */
    protected $contentType;

    /**
     * @var Header[]|null
     */
    protected $headers;

    /**
     * @var string|null
     */
    protected $style;

    /**
     * @var boolean|null
     */
    protected $explode;

    /**
     * @var boolean|null
     */
    protected $allowReserved;


    /**
     * Encoding constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("contentType"),
            new ModelProperty("headers", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, function () {
                return new Header();
            }),
            new ModelProperty("style"),
            new ModelProperty("explode"),
            new ModelProperty("allowReserved"),
        ]);
    }


    /**
     * @return string|null
     */
    public function getContentType(): ?string
    {
        return $this->contentType;
    }


    /**
     * @param string|null $contentType
     */
    public function setContentType(?string $contentType): void
    {
        $this->contentType = $contentType;
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
     * @return string|null
     */
    public function getStyle(): ?string
    {
        return $this->style;
    }


    /**
     * @param string|null $style
     */
    public function setStyle(?string $style): void
    {
        $this->style = $style;
    }


    /**
     * @return bool|null
     */
    public function getExplode(): ?bool
    {
        return $this->explode;
    }


    /**
     * @param bool|null $explode
     */
    public function setExplode(?bool $explode): void
    {
        $this->explode = $explode;
    }


    /**
     * @return bool|null
     */
    public function getAllowReserved(): ?bool
    {
        return $this->allowReserved;
    }


    /**
     * @param bool|null $allowReserved
     */
    public function setAllowReserved(?bool $allowReserved): void
    {
        $this->allowReserved = $allowReserved;
    }


}