<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use Nitria\ClassGenerator;
use Nitria\Method;
use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Model\Example;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Schema;

class ReferenceResolverGenerator
{

    /**
     * @var ClassGenerator
     */
    protected $classGenerator;

    /**
     * @var OpenAPI
     */
    protected $openAPI;


    /**
     * @param OpenAPI $openAPI
     * @param string $className
     * @param string $psrPrefix
     * @param string $basePath
     */
    public function generateReferenceResolver(OpenAPI $openAPI, string $className, string $psrPrefix, string $basePath)
    {
        $this->openAPI = $openAPI;
        $this->classGenerator = new ClassGenerator($className);
        $this->classGenerator->addImplements(ReferenceResolver::class);
        $this->classGenerator->addUsedClassName(InvalidReferenceException::class);

        $this->generateSetOpenAPI();
        $this->generateResolveSchema();
        $this->generateResolveExample();

        $this->classGenerator->writeToPSR4($basePath, $psrPrefix);
    }


    /**
     *
     */
    private function generateSetOpenAPI()
    {
        $method = $this->classGenerator->addMethod('setOpenAPI');
        $method->addParameter(OpenAPI::class, 'openAPI');
        $method->setReturnType('void', false);
    }


    /**
     *
     */
    private function generateResolveSchema()
    {
        $method = $this->classGenerator->addMethod("resolveSchema");
        $method->addException(InvalidReferenceException::class);
        $method->setReturnType(Schema::class, false);
        $method->addParameter(Schema::class, "object");

        $method->addIfStart('!$object->isReference()');
        $method->addCodeLine('return $object;');
        $method->addIfEnd();

        $method->addCodeLine('$object = new Schema();');
        $method->addCodeLine('$ref = $object->getRef();');

        $method->addSwitch('$ref');
        foreach ($this->getSchemaList() as $name => $schema) {
            $method->addSwitchCase('"#/components/schemas/' . $name . '"');
            $array = ArrayToString::compileArray($schema->jsonSerialize());
            $method->addCodeLine('$object->fromArray(' . $array . ')');
            $method->addSwitchBreak();
        }
        $method->addSwitchDefault();
        $this->addThrowException($method);

        $method->addSwitchEnd();

        $method->addCodeLine('return $object;');
    }


    /**
     * @return Schema[]
     */
    private function getSchemaList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $schemaList = $components->getSchemas();

        return $schemaList !== null ? $schemaList : [];
    }


    /**
     *
     */
    private function generateResolveExample()
    {
        $method = $this->classGenerator->addMethod("resolveExample");
        $method->addException(InvalidReferenceException::class);
        $method->setReturnType(Example::class, false);
        $method->addParameter(Example::class, "object");

        $method->addIfStart('!$object->isReference()');
        $method->addCodeLine('return $object;');
        $method->addIfEnd();

        $method->addCodeLine('$object = new Example();');
        $method->addCodeLine('$ref = $object->getRef();');

        $method->addSwitch('$ref');

        foreach ($this->getExampleList() as $name => $object) {
            $method->addSwitchCase('"#/components/examples/' . $name . '"');
            $array = ArrayToString::compileArray($object->jsonSerialize());
            $method->addCodeLine('$object->fromArray(' . $array . ')');
            $method->addSwitchBreak();
        }

        $method->addSwitchDefault();
        $this->addThrowException($method);

        $method->addSwitchEnd();

        $method->addCodeLine('return $object;');
    }




    /**
     * @return Schema[]
     */
    private function getExampleList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $list = $components->getExamples();

        return $list !== null ? $list : [];
    }

    const RESOLVER_CONFIG = [
        "resolveExample"
    ];


    /**
     *
     */
    private function generateResolver(string $methodName)
    {
        $method = $this->classGenerator->addMethod($methodName);
        $method->addException(InvalidReferenceException::class);
        $method->setReturnType(Schema::class, false);
        $method->addParameter(Schema::class, "object");

        $method->addIfStart('!$object->isReference()');
        $method->addCodeLine('return $object;');
        $method->addIfEnd();

        $method->addCodeLine('$object = new Schema();');
        $method->addCodeLine('$ref = $object->getRef();');

        $method->addSwitch('$ref');
        foreach ($this->getSchemaList() as $name => $schema) {
            $method->addSwitchCase('"#/components/schemas/' . $name . '"');
            $array = ArrayToString::compileArray($schema->jsonSerialize());
            $method->addCodeLine('$object->fromArray(' . $array . ')');
            $method->addSwitchBreak();
        }
        $method->addSwitchDefault();
        $this->addThrowException($method);

        $method->addSwitchEnd();

        $method->addCodeLine('return $object;');
    }




    private function addThrowException(Method $method)
    {
        $method->addCodeLine('throw new InvalidReferenceException("invalid reference" . $ref);');
    }


}