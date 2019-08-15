<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use Synatos\Porta\Model\Schema;

class SchemaToPHPGenerator
{
    const EXCEPTION_SCHEMA_TYPE_NOT_ALLOWED = "Schema type '%s' is not allowed";


    /**
     * @var ClassProperty[]
     */
    private $classPropertyList;

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
     * @param string|null $psrPrefix
     * @param string $basePath
     */
    public function __construct(?string $psrPrefix, string $basePath)
    {
        $this->schemaClassGenerator = new SchemaClassGenerator($psrPrefix, $basePath);
        $this->additionalSchemaList = [];
    }


    /**
     * @param string $namespace
     * @param string $className
     * @param Schema $schema
     */
    public function generateSchema(string $namespace, string $className, Schema $schema)
    {
        if (!$schema->isObject()) {
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
     * @return AdditionalProperties|null
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
     * @param Schema[]|null $schemaList
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
     * @return string
     */
    private function getPropertyType(string $name, Schema $schema): string
    {
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

        if ($schema->isArray()) {
            $itemType = $this->getPropertyType($name . 'Item', $schema->getItems());
            return $itemType . '[]';
        }

        if ($schema->isObject()) {
            $className = ucfirst($name);
            $this->addAdditionalClass($className, $schema);
            return $this->namespace . '\\' . $className;
        }
        $message = sprintf(self::EXCEPTION_SCHEMA_TYPE_NOT_ALLOWED, $schema->getType());
        throw new \RuntimeException($message);
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