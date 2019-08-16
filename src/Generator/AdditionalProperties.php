<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use Civis\Common\StringUtil;
use Synatos\Porta\Model\Schema;

class AdditionalProperties
{
    /**
     * @var Schema|null
     */
    private $schema;

    /**
     * @var string
     */
    private $type;

    /**
     * AdditionalProperties constructor.
     * @param Schema|null $schema
     * @param string|null $type
     */
    public function __construct(Schema $schema = null, string $type = null)
    {
        $this->schema = $schema;
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getClassName(): string
    {
        $className = trim($this->type, '[]');
        return StringUtil::getEndAfterLast($className, '\\');
    }


    /**
     * @return bool
     */
    public function isTypePrimitive()
    {
        if ($this->schema === null) {
            return true;
        }
        return !$this->schema->isObject() && !$this->schema->isArray();
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        if ($this->schema === null) {
            return false;
        }
        return $this->schema->isArray();
    }

    /**
     * @return bool
     */
    public function isObject(): bool
    {
        if ($this->schema === null) {
            return false;
        }
        return $this->schema->isObject();
    }

    /**
     * @return bool
     */
    public function isArrayOfObjects(): bool
    {
        if ($this->schema === null) {
            return false;
        }

        $itemSchema = $this->schema->getItems();
        return $this->schema->isArray() && $itemSchema->isObject();
    }

    /**
     * @return bool
     */
    public function isArrayOfPrimitives(): bool
    {
        if ($this->schema === null) {
            return false;
        }

        $itemSchema = $this->schema->getItems();
        return $this->schema->isArray() && !$itemSchema->isObject();

    }
}