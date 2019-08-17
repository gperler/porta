<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use Synatos\Porta\Model\Schema;

class ClassProperty
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var Schema
     */
    private $schema;


    /**
     * ClassProperty constructor.
     *
     * @param string $name
     * @param string $type
     * @param Schema $schema
     */
    public function __construct(string $name, string $type, Schema $schema)
    {
        $this->name = $name;
        $this->type = $type;
        $this->schema = $schema;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param string $prefix
     *
     * @return string
     */
    public function getMethodName(string $prefix): string
    {
        return $prefix . ucfirst($this->name);
    }


    /**
     * @return string
     */
    public function getClassName(): string
    {
        return ucfirst($this->name);
    }


    /**
     * @return string
     */
    public function getItemClassName(): string
    {
        return ucfirst($this->name) . 'Item';
    }


    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return !!$this->schema->isNullable();
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


    /**
     * @return array
     */
    public function getEnumList(): array
    {
        $enum = $this->schema->getEnum();
        return $enum ?: [];
    }


    /**
     * @return bool
     */
    public function isSchemaTypeString(): bool
    {
        return $this->schema->isString();
    }


    /**
     * @return bool
     */
    public function isTypeObject(): bool
    {
        return $this->schema->isObject() || $this->schema->isReference();
    }


    /**
     * @return bool
     */
    public function isArrayOfObject(): bool
    {
        if (!$this->schema->isArray()) {
            return false;
        }
        $itemsSchema = $this->schema->getItems();
        if ($itemsSchema === null) {
            return false;
        }
        return $itemsSchema->isObject() || $itemsSchema->isReference();
    }


    /**
     * @return bool
     */
    public function isObjectOrArrayOfObject(): bool
    {
        return $this->isTypeObject() || $this->isArrayOfObject();
    }

}