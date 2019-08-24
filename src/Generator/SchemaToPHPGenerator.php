<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use RuntimeException;
use Synatos\Porta\Contract\ReferenceClassResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Model\Schema;

class SchemaToPHPGenerator
{
    const EXCEPTION_SCHEMA_TYPE_NOT_ALLOWED = "Schema type '%s' is not allowed";


    /**
     * @var ClassProperty[]
     */
    private $classPropertyList;

    /**
     * @var ReferenceClassResolver
     */
    private $referenceClassResolver;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var Schema[]
     */
    private $additionalSchemaList;

    /**
     * @var SchemaClassGenerator
     */
    private $schemaClassGenerator;


    /**
     * SchemaToPHPGenerator constructor.
     *
     * @param string|null $psrPrefix
     * @param string $basePath
     * @param ReferenceClassResolver $referenceClassResolver
     */
    public function __construct(?string $psrPrefix, string $basePath, ReferenceClassResolver $referenceClassResolver)
    {
        $this->schemaClassGenerator = new SchemaClassGenerator($psrPrefix, $basePath);
        $this->additionalSchemaList = [];
        $this->referenceClassResolver = $referenceClassResolver;
    }


    /**
     * @param string $namespace
     * @param string $className
     * @param Schema $schema
     *
     * @throws InvalidReferenceException
     */
    public function generateSchema(string $namespace, string $className, Schema $schema)
    {
        if (!$schema->isObject() || $schema->isReference()) {
            return;
        }
        $this->namespace = $namespace;
        $this->classPropertyList = [];
        $this->addSchemaToClassPropertyList($schema);

        $additionalProperties = $this->getAdditionalProperties($schema, $className);
        $this->schemaClassGenerator->generate($this->classPropertyList, $namespace, $className, $additionalProperties);

        $nextToGenerate = array_keys($this->additionalSchemaList);
        if (count($nextToGenerate) === 0) {
            return;
        }

        $nextName = $nextToGenerate[0];
        $nextSchema = $this->additionalSchemaList[$nextName];
        unset($this->additionalSchemaList[$nextName]);

        $this->generateSchema($namespace, ucfirst($nextName), $nextSchema);
    }


    /**
     * @param Schema $schema
     * @param string $className
     *
     * @return AdditionalProperties|null
     * @throws InvalidReferenceException
     */
    private function getAdditionalProperties(Schema $schema, string $className): ?AdditionalProperties
    {
        $additionalProperties = $schema->getAdditionalProperties();
        if ($additionalProperties === null || $additionalProperties === false) {
            return null;
        }
        if ($additionalProperties === true) {
            return new AdditionalProperties();
        }
        $type = $this->getPropertyType($className . "AdditionalProperties", $additionalProperties);

        return new AdditionalProperties($additionalProperties, $type);
    }


    /**
     * @param Schema $schema
     *
     * @throws InvalidReferenceException
     */
    private function addSchemaToClassPropertyList(Schema $schema)
    {
        $properties = $schema->getProperties();
        if ($properties === null) {
            return;
        }

        foreach ($properties as $name => $propertySchema) {
            $type = $this->getPropertyType($name, $propertySchema);
            $this->classPropertyList[] = new ClassProperty($name, $type, $propertySchema);
        }

        $this->addSchemaListToClassPropertyList($schema->getOneOf());
        $this->addSchemaListToClassPropertyList($schema->getAnyOf());
        $this->addSchemaListToClassPropertyList($schema->getAllOf());
    }


    /**
     * @param array|null $schemaList
     *
     * @throws InvalidReferenceException
     */
    private function addSchemaListToClassPropertyList(array $schemaList = null)
    {
        if ($schemaList === null) {
            return;
        }
        foreach ($schemaList as $schema) {
            $this->addSchemaToClassPropertyList($schema);
        }
    }


    /**
     * @param string $name
     * @param Schema $schema
     *
     * @return string
     * @throws InvalidReferenceException
     */
    private function getPropertyType(string $name, Schema $schema): string
    {
        if ($schema->isReference()) {
            return $this->referenceClassResolver->getClassNameForReference($schema->getRef());
        }

        if ($schema === null) {
            return "mixed";
        }

        if ($schema->isBoolean()) {
            return "bool";
        }

        if ($schema->isInteger()) {
            return "int";
        }

        if ($schema->isNumber()) {
            return "float";
        }

        if ($schema->isString()) {
            return "string";
        }

        if ($schema->isArray() && $schema->getItems() !== null) {
            $items = $schema->getItems();
            if ($items->isReference()) {
                return $this->referenceClassResolver->getClassNameForReference($items->getRef()) . '[]';
            }

            $itemType = $this->getPropertyType($name . 'Item', $schema->getItems());
            return $itemType . '[]';
        }

        if ($schema->isArray() && $schema->getItems() === null) {
            return 'array';
        }


        if ($schema->isObject()) {
            $className = ucfirst($name);
            $this->addAdditionalClass($className, $schema);
            return $this->namespace . '\\' . $className;
        }
        $message = sprintf(self::EXCEPTION_SCHEMA_TYPE_NOT_ALLOWED, $schema->getType());
        throw new RuntimeException($message);
    }


    /**
     * @param string $className
     * @param Schema $schema
     */
    private function addAdditionalClass(string $className, Schema $schema)
    {
        if ($schema->getType() === null) {
            return;
        }
        $this->additionalSchemaList[$className] = $schema;
    }


}