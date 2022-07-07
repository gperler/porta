<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use JsonSerializable;
use Nitria\ClassGenerator;
use Nitria\Method;

class SchemaClassGenerator
{
    const ADDITIONAL_PROPERTIES = 'additionalProperties';

    /**
     * @var string
     */
    private $psrPrefix;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var ClassProperty[]
     */
    private $classPropertyList;

    /**
     * @var ClassGenerator
     */
    private $classGenerator;


    /**
     * @var AdditionalProperties
     */
    private $additionalProperties;


    /**
     * SchemaClassGenerator constructor.
     *
     * @param string $psrPrefix
     * @param string $basePath
     */
    public function __construct(string $psrPrefix, string $basePath)
    {
        $this->psrPrefix = $psrPrefix;
        $this->basePath = $basePath;
    }


    /**
     * @param array $classPropertyList
     * @param string $namespace
     * @param string $className
     * @param AdditionalProperties|null $additionalProperties
     */
    public function generate(array $classPropertyList, string $namespace, string $className, ?AdditionalProperties $additionalProperties)
    {
        $this->classPropertyList = $classPropertyList;
        $this->additionalProperties = $additionalProperties;

        $this->prepareClassGenerator($namespace, $className);

        $this->addPropertiesAndGetterSetter();

        $this->addAdditionalProperties();

        $this->addFromArray();

        $this->addJsonSerialize();

        $this->classGenerator->writeToPSR4($this->basePath, $this->psrPrefix);
    }


    /**
     * @param string $namespace
     * @param string $className
     */
    private function prepareClassGenerator(string $namespace, string $className)
    {
        $this->classGenerator = new ClassGenerator($namespace . '\\' . $className);
        $this->classGenerator->addImplements(JsonSerializable::class);
    }


    /**
     *
     */
    private function addPropertiesAndGetterSetter()
    {
        foreach ($this->classPropertyList as $classProperty) {
            $this->addProperty($classProperty);

            $this->addSetter($classProperty);
            $this->addGetter($classProperty);

            $this->addEnumConstant($classProperty);
            $this->addConstantSetter($classProperty);
            $this->addConstantGetter($classProperty);
        }
    }


    /**
     * @param ClassProperty $classProperty
     */
    private function addProperty(ClassProperty $classProperty)
    {
        $this->classGenerator->addProtectedProperty($classProperty->getName(), $classProperty->getType(), 'null');
    }


    /**
     * @param ClassProperty $classProperty
     */
    private function addSetter(ClassProperty $classProperty)
    {
        $name = $classProperty->getName();
        $methodName = $classProperty->getMethodName("set");
        $phpType = $classProperty->getType();
        $isNullable = $classProperty->isNullable();

        $setter = $this->classGenerator->addPublicMethod($methodName);
        $setter->addParameter($phpType, $name, null, null, $isNullable);
        $setter->addCodeLine('$this->' . $name . ' = $' . $name . ';');
    }


    /**
     * @param ClassProperty $classProperty
     */
    private function addGetter(ClassProperty $classProperty)
    {
        $name = $classProperty->getName();
        $methodName = $classProperty->getMethodName("get");
        $phpType = $classProperty->getType();
        $isNullable = $classProperty->isNullable();

        $getter = $this->classGenerator->addPublicMethod($methodName);
        $getter->setReturnType($phpType, $isNullable);
        $getter->addCodeLine('return $this->' . $name . ';');
    }


    /**
     * @param ClassProperty $classProperty
     */
    private function addEnumConstant(ClassProperty $classProperty)
    {
        $name = $classProperty->getName();

        foreach ($classProperty->getEnumList() as $enumValue) {
            $constantName = $this->getEnumConstantName($name, $enumValue);
            if ($classProperty->isSchemaTypeString()) {
                $enumValue = '"' . $enumValue . '"';
            }
            $this->classGenerator->addConstant($constantName, $enumValue);
        }
    }


    /**
     * @param ClassProperty $classProperty
     */
    private function addConstantSetter(ClassProperty $classProperty)
    {
        $name = $classProperty->getName();

        foreach ($classProperty->getEnumList() as $enumValue) {
            $constantName = $this->getEnumConstantName($name, $enumValue);

            $methodName = "set" . ucfirst($name) . ucfirst(strtolower($enumValue));
            $method = $this->classGenerator->addMethod($methodName);
            $method->addCodeLine('$this->' . $name . ' = self::' . $constantName . ';');
        }
    }


    /**
     * @param ClassProperty $classProperty
     */
    private function addConstantGetter(ClassProperty $classProperty)
    {
        $name = $classProperty->getName();

        foreach ($classProperty->getEnumList() as $enumValue) {
            $constantName = $this->getEnumConstantName($name, $enumValue);
            $methodName = "is" . ucfirst($name) . ucfirst(strtolower($enumValue));
            $method = $this->classGenerator->addMethod($methodName);
            $method->setReturnType("bool", false);
            $method->addCodeLine('return $this->' . $name . ' === self::' . $constantName . ';');
        }
    }


    /**
     * @param string $name
     * @param $enumValue
     *
     * @return string
     */
    private function getEnumConstantName(string $name, $enumValue): string
    {
        return ltrim(strtoupper(preg_replace('/[A-Z]/', '_$0', $name)), '_') . strtoupper('_' . $enumValue);
    }


    /**
     *
     */
    private function addAdditionalProperties()
    {
        if ($this->additionalProperties === null) {
            return;
        }

        $type = $this->getAdditionalPropertiesType();

        $this->addAdditionalPropertiesProperty($type);
        $this->addAdditionalPropertiesSetter($type);
        $this->addAdditionalPropertiesGetter($type);
    }


    /**
     * @param string $type
     */
    private function addAdditionalPropertiesProperty(string $type)
    {
        $this->classGenerator->addProtectedProperty(self::ADDITIONAL_PROPERTIES, $type, 'null', null);
    }


    /**
     * @param string $type
     */
    private function addAdditionalPropertiesSetter(string $type)
    {
        $setter = $this->classGenerator->addPublicMethod("setAdditionalProperties");
        $setter->addParameter($type, self::ADDITIONAL_PROPERTIES, null, null, true);
        $setter->addCodeLine('$this->' . self::ADDITIONAL_PROPERTIES . ' = $' . self::ADDITIONAL_PROPERTIES . ';');
    }


    /**
     * @param string $type
     */
    private function addAdditionalPropertiesGetter(string $type)
    {
        $getter = $this->classGenerator->addPublicMethod("getAdditionalProperties");
        $getter->setReturnType($type, true);
        $getter->addCodeLine('return $this->' . self::ADDITIONAL_PROPERTIES . ';');
    }


    /**
     * @return string|null
     */
    private function getAdditionalPropertiesType(): ?string
    {
        if ($this->additionalProperties === null) {
            return null;
        }
        $type = $this->additionalProperties->getType();
        return $type === null ? 'array' : $type . '[]';
    }


    private function addFromArray()
    {
        $method = $this->classGenerator->addPublicMethod("fromArray");
        $method->addParameter("array", "array", null, null, false);

        $method->addForeachStart('$array as $propertyName => $propertyValue');
        $method->addSwitch('$propertyName');

        $this->addFromArrayClassPropertyList($method);

        $this->addFromArrayAdditionalProperties($method);

        $method->addSwitchEnd();

        $method->addForeachEnd();
    }


    /**
     * @param Method $method
     */
    private function addFromArrayClassPropertyList(Method $method)
    {
        foreach ($this->classPropertyList as $classProperty) {
            $this->addFromArrayClassProperty($method, $classProperty);
        }
    }


    /**
     * @param Method $method
     * @param ClassProperty $classProperty
     */
    private function addFromArrayClassProperty(Method $method, ClassProperty $classProperty)
    {
        $propertyName = $classProperty->getName();
        $method->addSwitchCase('"' . $propertyName . '"');

        if ($classProperty->isTypeObject()) {
            $method->addIfStart('$propertyValue !== null');
            $this->addFromArrayObjectProperty($method, $classProperty);
            $method->addIfEnd();
        }

        if ($classProperty->isArrayOfObject()) {
            $method->addIfStart('$propertyValue !== null');
            $this->addFromArrayArrayOfObjectProperty($method, $classProperty);
            $method->addIfEnd();
        }

        if (!$classProperty->isObjectOrArrayOfObject()) {
            $this->addFromArraySimpleProperty($method, $classProperty);
        }
        $method->addSwitchBreak();
    }


    /**
     * @param Method $method
     * @param ClassProperty $classProperty
     */
    private function addFromArraySimpleProperty(Method $method, ClassProperty $classProperty)
    {
        $propertyName = $classProperty->getName();
        $method->addCodeLine('$this->' . $propertyName . ' = $propertyValue;');
    }


    /**
     * @param Method $method
     * @param ClassProperty $classProperty
     */
    private function addFromArrayObjectProperty(Method $method, ClassProperty $classProperty): void
    {
        $name = $classProperty->getName();
        $className = $classProperty->getClassName();
        $method->addCodeLine('$this->' . $name . ' = new ' . $className . '();');
        $method->addCodeLine('$this->' . $name . '->fromArray($propertyValue);');
    }


    /**
     * @param Method $method
     * @param ClassProperty $classProperty
     */
    private function addFromArrayArrayOfObjectProperty(Method $method, ClassProperty $classProperty): void
    {
        $name = $classProperty->getName();
        $className = $classProperty->getItemClassName();

        $method->addForeachStart('$propertyValue as $key => $item');

        $method->addCodeLine('$itemObject = new ' . $className . '();');
        $method->addCodeLine('$itemObject->fromArray($item);');
        $method->addCodeLine('$this->' . $name .'[$key] = $itemObject;');




        $method->addForeachEnd();
    }


    /**
     * @param Method $method
     */
    private function addFromArrayAdditionalProperties(Method $method)
    {
        if ($this->additionalProperties === null) {
            return;
        }

        $method->addSwitchDefault();

        if ($this->additionalProperties->isTypePrimitive() || $this->additionalProperties->isArrayOfPrimitives()) {
            $method->addCodeLine('$this->' . self::ADDITIONAL_PROPERTIES . '[$propertyName] = $propertyValue;');
        }

        if ($this->additionalProperties->isObject()) {
            $className = $this->additionalProperties->getClassName();
            $method->addIfStart('$propertyValue !== null');
            $method->addCodeLine('$additionalProperty = new ' . $className . '();');
            $method->addCodeLine('$additionalProperty->fromArray($propertyValue);');
            $method->addCodeLine('$this->' . self::ADDITIONAL_PROPERTIES . '[$propertyName] = $additionalProperty;');
            $method->addIfElse();
            $method->addCodeLine('$this->' . self::ADDITIONAL_PROPERTIES . '[$propertyName] = null;');
            $method->addIfEnd();
        }

        if ($this->additionalProperties->isArrayOfObjects()) {
            $className = $this->additionalProperties->getClassName();
            $method->addIfStart('$propertyValue !== null');
            $method->addCodeLine('$this->' . self::ADDITIONAL_PROPERTIES  .'[$propertyName] = [];');

            $method->addForeachStart('$propertyValue as $key => $item');
            $method->addCodeLine('$itemObject = new ' . $className . '();');
            $method->addCodeLine('$itemObject->fromArray($item);');
            $method->addCodeLine('$this->' . self::ADDITIONAL_PROPERTIES  .'[$propertyName][$key] = $itemObject;');
            $method->addForeachEnd();
            $method->addIfElse();

            $method->addCodeLine('$this->' . self::ADDITIONAL_PROPERTIES  .'[$propertyName] = null;');


            $method->addIfEnd();
        }

        $method->addSwitchBreak();
    }


    /**
     *
     */
    private function addJsonSerialize()
    {
        $method = $this->classGenerator->addPublicMethod("jsonSerialize");
        $method->setReturnType("array", false);

        $this->addJsonSerializeStartArray($method);

        $this->addJsonSerializeProperties($method);

        $this->addJsonSerializeEndArray($method);
    }


    /**
     * @param Method $method
     */
    private function addJsonSerializeStartArray(Method $method)
    {
        if ($this->additionalProperties === null) {
            $method->addCodeLine('return [');
        } else {
            $method->addCodeLine('return array_merge([');
        }
        $method->incrementIndent();
    }


    /**
     * @param Method $method
     */
    private function addJsonSerializeProperties(Method $method)
    {
        foreach ($this->classPropertyList as $classProperty) {
            $name = $classProperty->getName();
            $method->addCodeLine('"' . $name . '" => $this->' . $name . ',');
        }
    }


    /**
     * @param Method $method
     */
    private function addJsonSerializeEndArray(Method $method)
    {
        $method->decrementIndent();

        if ($this->additionalProperties === null) {
            $method->addCodeLine('];');
        } else {
            $method->addCodeLine('], $this->' . self::ADDITIONAL_PROPERTIES . ');');
        }
    }


}