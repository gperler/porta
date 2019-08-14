<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Response extends ArraySerializableModel
{

    use ReferenceAble;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var Header[]|null
     */
    protected $headers;

    /**
     * @var MediaType[]|null
     */
    protected $content;

    /**
     * @var Link[]
     */
    protected $links;


    /**
     * Response constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty('$ref', ModelProperty::TYPE_BUILD_IN, null, null, 'ref'),
            new ModelProperty("description"),
            new ModelProperty("headers", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new Header();
            }),
            new ModelProperty("content", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new MediaType();
            }),
            new ModelProperty("links", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, static function () {
                return new Link();
            })
        ]);
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
     * @return MediaType[]|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }


    /**
     * @param MediaType[]|null $content
     */
    public function setContent(?array $content): void
    {
        $this->content = $content;
    }


    /**
     * @param string $contentType
     *
     * @return MediaType|null
     */
    public function getContentByType(string $contentType = null): ?MediaType
    {
        if ($contentType === null) {
            return null;
        }
        $contentType = strtolower($contentType);
        if (!isset($this->content[$contentType])) {
            return null;
        }
        return $this->content[$contentType];
    }


    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }


    /**
     * @param Link[] $links
     */
    public function setLinks(array $links): void
    {
        $this->links = $links;
    }

}