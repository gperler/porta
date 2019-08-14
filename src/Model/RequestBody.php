<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class RequestBody extends ArraySerializableModel
{

    use ReferenceAble;


    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var MediaType[]
     */
    protected $content;

    /**
     * @var bool|null
     */
    protected $required;


    /**
     * RequestBody constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty('$ref', ModelProperty::TYPE_BUILD_IN, null, null, 'ref'),
            new ModelProperty("description"),
            new ModelProperty("content", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, function () {
                return new MediaType();
            }),
            new ModelProperty("required")
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
     * @return MediaType[]
     */
    public function getContent(): array
    {
        return $this->content;
    }


    /**
     * @param string $contentType
     *
     * @return MediaType
     */
    public function getContentByType(?string $contentType): ?MediaType
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
     * @param MediaType[] $content
     */
    public function setContent(array $content): void
    {
        $this->content = $content;
    }


    /**
     * @return bool|null
     */
    public function getRequired(): ?bool
    {
        return $this->required;
    }


    /**
     * @param bool|null $required
     */
    public function setRequired(?bool $required): void
    {
        $this->required = $required;
    }


}